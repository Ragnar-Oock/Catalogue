<?php

namespace App\Controller;

use App\Entity\ParticipationType;
use App\Form\ParticipationTypeType;
use App\Repository\ParticipationTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/participation")
 */
class ParticipationTypeController extends AbstractController
{
    /**
     * @Route("/", name="participation_type_index", methods={"GET"})
     */
    public function index(ParticipationTypeRepository $participationTypeRepository): Response
    {
        return $this->render('participation_type/index.html.twig', [
            'participation_types' => $participationTypeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ajouter", name="participation_type_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $participationType = new ParticipationType();
        $form = $this->createForm(ParticipationTypeType::class, $participationType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($participationType);
            $entityManager->flush();

            $this->addFlash('success', 'Nouveau type de participation ajouté avec succes');

            return $this->redirectToRoute('participation_type_index');
        }

        return $this->render('participation_type/new.html.twig', [
            'participation_type' => $participationType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/modifier", name="participation_type_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ParticipationType $participationType): Response
    {
        $form = $this->createForm(ParticipationTypeType::class, $participationType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Modifications enregistrées');


            return $this->redirectToRoute('participation_type_index');
        }

        return $this->render('participation_type/edit.html.twig', [
            'participation_type' => $participationType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="participation_type_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ParticipationType $participationType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participationType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($participationType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('participation_type_index');
    }
}
