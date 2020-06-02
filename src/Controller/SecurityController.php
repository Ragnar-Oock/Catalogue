<?php

namespace App\Controller;

use App\Form\ChangePSWType;
use App\Form\ResetPSWType;
use App\Form\UptPswType;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/connexion", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('site');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/deconnexion", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/mot-de-passe-perdu", name="app_request_psw")
     */
    public function requestPassword(UserRepository $ur, Request $request, MailerInterface $mailer)
    {
        // if the user is already logged on redirect to profil page
        if ($this->getUser()) {
            return $this->redirectToRoute('profil_index');
        }

        $form = $this->createForm(ResetPSWType::class);
        $form->handleRequest($request);

        // is the form is submited
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $ur->findOneBy(['email' => $form->getData()['email']]);

            // if the user is known send reset link
            if ($user) {
                $user->setToken(uniqid());

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $mail = (new TemplatedEmail())
                    ->from($_ENV['ADMIN_MAIL'])
                    ->to($user->getEmail())
                    ->subject('Réinitialisez votre mot de passe')
                    ->htmlTemplate('mail/reset_psw.html.twig')
                    ->context([
                        'token' => $user->getToken()
                    ]);
                $mailer->send($mail);
            }
            // anyway, display "you should have received a mail" message
            return $this->render('security/confirm_psw_request.html.twig');
        }
        // if the user just loaded in
        else {
            return $this->render('security/request_psw.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }

    /**
     * @Route("/changer-de-mot-de-passe/{token}", name="app_change_psw")
     */
    public function resetPassword(Request $request, UserRepository $ur, UserPasswordEncoderInterface $encoder)
    {
        $token = $request->attributes->get('token');

        if ($token) {
            $user = $ur->findOneBy(['token' => $token]);

            if ($user) {
                $form = $this->createForm(ChangePSWType::class, $user);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $psw = $form->getData()->getPassword();
                    $encodedPsw = $encoder->encodePassword($user, $psw);

                    $user->setPassword($encodedPsw);
                    $user->setToken('');

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();

                    $this->addFlash('success', 'Mot de passe mis a jour');

                    return $this->redirectToRoute('profil_index');
                } else {
                    return $this->render('security/request_psw.html.twig', [
                        'form' => $form->createView()
                    ]);
                }
            } else {
                return $this->redirectToRoute('app_request_psw');
            }
        } else {
            return $this->redirectToRoute('site');
        }
    }


    /**
     * @Route("/profil/changer-de-mot-de-passe", name="profil_change_psw")
     */
    public function changePsw(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(UptPswType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentPsw = $form->getData()['verifyPassword'];
            $newPsw = $form->getData()['password'];
            if ($encoder->isPasswordValid($user, $currentPsw)) {
                $encodedNewPsw = $encoder->encodePassword($user, $newPsw);

                $user->setPassword($encodedNewPsw);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'Mot de passe mis à jour');

                return $this->redirectToRoute('profil_index');
            } else {
                $this->addFlash('danger', 'Votre mot de passe est incorrect');

                return $this->render(
                    'security/request_psw.html.twig',
                    [
                        'form' => $form->createView()
                    ]
                );
            }
        } else {
            return $this->render(
                'security/request_psw.html.twig',
                [
                    'form' => $form->createView()
                ]
            );
        }
    }
}
