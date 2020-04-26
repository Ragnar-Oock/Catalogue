<?php

namespace App\Controller;

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
        $results = $editionRepository->searchEdition($request->query->get('search') || null);
        $results = $paginator->paginate(
            $results,
            $request->query->get('page', 1),
            30
        );

        return $this->render('site/search/results.html.twig', [
            'controller_name' => 'SearchController',
            'resultList' => $results    
        ]);
    }
}
