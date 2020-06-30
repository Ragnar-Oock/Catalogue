<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/profil")
 */
class ProfilController extends AbstractController
{

    /**
     * @Route("/", name="profil_index", methods={"GET","POST"})
     */
    public function edit(Request $request, UserRepository $ur): Response
    {
        $user = $this->getUser();
        $email = $request->request->get('profile')['email'];
        $form = $this->createForm(ProfileType::class, $user);

        // dd($ur->isKnownEmail($email) !== 0, $user->getEmail() !== $email, $email, $user->getEmail());

        if ($ur->isKnownEmail($email) !== 0 && $user->getEmail() !== $email) {
            return $this->render('site/profil/edit.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
                'knownEmail' => true
            ]);
        }
        else {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();           
            }
        }

    return $this->render('site/profil/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/supprimmer-mon-compte", name="account_delete", methods={"DELETE"})
     */
    public function delete(Request $request): Response
    {
        $user = $this->getUser();
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            // dont remove the user entity as its linked to potencially many reservations we need to keep track of
            // $entityManager->remove($user);

            // only remove personnal data
            $user->setEmail(null);
            $user->setFirstname(null);
            $user->setLastname(null);
            $user->setRoles([""]);
            $entityManager->flush();

            $session = new Session();
            $session->invalidate();

            return $this->redirectToRoute('site');

        }
        else {
            $this->addFlash('danger', "une erreur est survenue lors de la suppression de votre compte");
        }
        return $this->redirectToRoute('profil_index');
    }
}
