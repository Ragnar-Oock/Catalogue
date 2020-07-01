<?php

namespace App\Controller;

use App\Entity\Editor;
use App\Form\EditorType;
use App\Repository\EditorRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/editeur")
 */
class EditorController extends AbstractController
{
    /**
     * @Route("/", name="editor_index", methods={"GET"})
     */
    public function index(Request $request, PaginatorInterface $paginator, EditorRepository $editorRepository): Response
    {
        $editors = $editorRepository->findAll();
        $editors = $paginator->paginate(
            $editors,
            $request->query->get('page', 1),
            15
        );
        return $this->render('admin/editor/index.html.twig', [
            'editors' => $editors,
        ]);
    }

    /**
     * @Route("/ajouter", name="editor_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $editor = new Editor();
        $form = $this->createForm(EditorType::class, $editor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($editor);
            $entityManager->flush();

            $this->addFlash('success', 'Nouvel éditeur ajouté avec succes');

            return $this->redirectToRoute('editor_index');
        }

        return $this->render('admin/editor/new.html.twig', [
            'editor' => $editor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/modifier", name="editor_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Editor $editor): Response
    {
        $form = $this->createForm(EditorType::class, $editor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Modifications enregistrées');

            return $this->redirectToRoute('editor_index');
        }

        return $this->render('admin/editor/edit.html.twig', [
            'editor' => $editor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="editor_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Editor $editor): Response
    {
        if ($this->isCsrfTokenValid('delete'.$editor->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($editor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('editor_index');
    }
}
