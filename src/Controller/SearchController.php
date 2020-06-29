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
        $filters = [
            'publisheAfter' => !empty($values['publisheAfter']) ? \DateTime::createFromFormat('d/m/Y', $values['publisheAfter']) : null,
            'publishedBefore' => !empty($values['publishedBefore']) ? \DateTime::createFromFormat('d/m/Y', $values['publishedBefore']) : null,
            'type' => $values['type'],
            'title' => $values['title'],
            'author' => $values['author'],
            'editor' => $values['editor'],
            'issn' => $values['issn'],
            'isbn' => $values['isbn'],
        ];

        // filter all falsy values (i.e. empty fields)
        $filters = array_filter($filters);
        // store the number of actual filter (i.e. all but the search bar)
        $filterCount = count($filters);

        // affect the value from the nav bar search form (may be null)
        $search = $request->get('search');
        // if the search is null (i.e. the search don't originate from the nav bar)
        if (!empty($values['search'])) {
            // affect the value from the search form (may be null, if so it will not be taken in count)
            $search = $values['search'];
        }

        // inject back the search value in the values array
        $filters['search'] = $search;



        $form = $this->createForm(AdvencedSearchFormType::class, $filters, [
            'action' => $this->generateUrl('search'),
            'method' => 'GET',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $results = $editionRepository->advencedSearchEdition($filters);
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
            'form' => $form->createView(),
            // number of filter applied
            'filterCount' => $filterCount
        ]);
    }
}
