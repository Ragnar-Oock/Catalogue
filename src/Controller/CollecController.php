<?php

namespace App\Controller;

use App\Entity\Collec;
use App\Form\CollecType;
use App\Repository\CollecRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/collec")
 */
class CollecController extends AbstractController
{
    /**
     * @Route("/", name="collec_index", methods={"GET"})
     */
    public function index(CollecRepository $collecRepository): Response
    {
        return $this->render('admin/collec/index.html.twig', [
            'collecs' => $collecRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ajouter", name="collec_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $collec = new Collec();
        $form = $this->createForm(CollecType::class, $collec);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($collec);
            $entityManager->flush();

            return $this->redirectToRoute('collec_index');
        }

        return $this->render('admin/collec/new.html.twig', [
            'collec' => $collec,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="collec_show", methods={"GET"})
     */
    public function show(Collec $collec): Response
    {
        return $this->render('admin/collec/show.html.twig', [
            'collec' => $collec,
        ]);
    }

    /**
     * @Route("/{id}/modifier", name="collec_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Collec $collec): Response
    {
        $form = $this->createForm(CollecType::class, $collec);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('collec_index');
        }

        return $this->render('admin/collec/edit.html.twig', [
            'collec' => $collec,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="collec_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Collec $collec): Response
    {
        if ($this->isCsrfTokenValid('delete'.$collec->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($collec);
            $entityManager->flush();
        }

        return $this->redirectToRoute('collec_index');
    }
}
