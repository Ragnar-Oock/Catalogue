<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/profil")
 */
class ProfilController extends AbstractController
{

    /**
     * @Route("/", name="profil_index", methods={"GET","POST"})
     */
    public function edit(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profil_index');
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
            $user->setEmail('');
            $user->setFirstname('');
            $user->setLastname('');
            $user->setRoles([]);
            $entityManager->flush();
        }

        return $this->redirect($this->generateUrl('app_logout'));
    }
}
