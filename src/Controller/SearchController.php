<?php

namespace App\Controller;

use App\Form\AdvencedSearchFormType;
use App\Repository\EditionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/recherche", name="search")
     */
    public function index(EditionRepository $editionRepository, PaginatorInterface $paginator, Request $request)
    {
        $values = $request->get('advenced_search_form');
        $values = [
            'publisheAfter' => !empty($values['publisheAfter']) ? \DateTime::createFromFormat('d/m/Y', $values['publisheAfter']) : null,
            'publishedBefore' => !empty($values['publishedBefore']) ? \DateTime::createFromFormat('d/m/Y', $values['publishedBefore']) : null,
            'type' => $values['type'],
            'title' => $values['title'],
            'author' => $values['author'],
            'editor' => $values['editor'],
            'issn' => $values['issn'],
            'isbn' => $values['isbn'],
        ];
        $form = $this->createForm(AdvencedSearchFormType::class, $values, [
            'action' => $this->generateUrl('search'),
            'method' => 'GET',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $results = $editionRepository->advencedSearchEdition($values);
        }
        else {
            $results = $editionRepository->searchEdition($request->query->get('search', ''));
        }
        $results = $paginator->paginate(
            $results,
            $request->query->get('page', 1),
            30
        );

        return $this->render('site/search/results.html.twig', [
            'resultList' => $results,
            'form' => $form->createView()
        ]);
    }
}
