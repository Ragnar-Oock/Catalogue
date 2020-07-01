<?php

namespace App\Controller;

use App\Entity\Edition;
use App\Form\EditionType;
use App\Repository\EditionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/edition")
 */
class EditionController extends AbstractController
{
    /**
     * @Route("/", name="edition_index", methods={"GET"})
     */
    public function index(Request $request, PaginatorInterface $paginator, EditionRepository $editionRepository): Response
    {
        $editions = $editionRepository->findAll();
        $editions = $paginator->paginate(
            $editions,
            $request->query->get('page', 1),
            15
        );
        return $this->render('admin/edition/index.html.twig', [
            'editions' => $editions,
        ]);
    }

    /**
     * @Route("/ajouter", name="edition_new", methods={"GET","POST"})
     */
    public function new(Request $request, EditionRepository $er): Response
    {
        $edition = new Edition();
        $edition->setInventoryNumber($er->getNewInventoryNumber());
        
        $form = $this->createForm(EditionType::class, $edition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($edition);
            $entityManager->flush();

            $this->addFlash('success', 'Nouvelle édition ajoutée avec succes. maintenant ajoutez des auteur à cette édition.');

            return $this->redirectToRoute('writer_index', ['edition' => $edition->getId()]);
        }

        return $this->render('admin/edition/new.html.twig', [
            'edition' => $edition,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{edition}/modifier", name="edition_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Edition $edition): Response
    {
        $form = $this->createForm(EditionType::class, $edition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Modifications enregistrées');


            return $this->redirectToRoute('edition_index');
        }

        return $this->render('admin/edition/edit.html.twig', [
            'edition' => $edition,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="edition_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Edition $edition): Response
    {
        if ($this->isCsrfTokenValid('delete'.$edition->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($edition);
            $entityManager->flush();
        }

        return $this->redirectToRoute('edition_index');
    }
}
