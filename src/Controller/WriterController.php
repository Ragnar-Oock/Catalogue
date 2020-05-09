<?php

namespace App\Controller;

use App\Entity\Edition;
use App\Entity\Writer;
use App\Form\WriterType;
use App\Repository\WriterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// /**
//  * @Route("/writer")
//  */
class WriterController extends AbstractController
{
    /**
     * @Route("/edition/{edition}/participants/gerer", name="writer_index", methods={"GET"})
     */
    public function index(WriterRepository $writerRepository, Edition $edition): Response
    {
        return $this->render('admin/writer/index.html.twig', [
            'edition' => $edition,
            'writers' => $writerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/edition/{edition}/participants/ajouter", name="writer_add", methods={"GET","POST"})
     */
    public function new(Request $request, Edition $edition): Response
    {
        $writer = new Writer();
        $form = $this->createForm(WriterType::class, $writer);
        $form->handleRequest($request);
        $writer->setEdition($edition);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($writer);
            $entityManager->flush();

            $nextAction = $form->get('saveAndAddMore')->isClicked()
                ? 'writer_add'
                : 'edition_edit';

            return $this->redirectToRoute($nextAction, ['id' => $edition]);
        }

        return $this->render('admin/writer/new.html.twig', [
            'edition' => $edition,
            'writer' => $writer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="writer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Writer $writer): Response
    {
        $form = $this->createForm(WriterType::class, $writer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('writer_index');
        }

        return $this->render('admin/writer/edit.html.twig', [
            'writer' => $writer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="writer_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Writer $writer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$writer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($writer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('writer_index');
    }
}
