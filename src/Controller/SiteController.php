<?php

namespace App\Controller;

use App\Form\SearchFormType;
use App\Repository\EditionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route("/", name="site")
     */
    public function index()
    {
        return $this->render('site/index.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }

    /**
     * no route as this function is only called from twig templates
     */
    public function search()
    {
        $form = $this->createForm(SearchFormType::class);
        return $this->render('site/search/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function showSearchResults(Request $request, PaginatorInterface $paginator, EditionRepository $editionRepository)
    {
        $search = $request->query->get('search') != null ? $request->query->get('search') : null;

        if ($search === null) {
            $res = $editionRepository->findAll();
        }
        else {
            $res = $editionRepository->searchEdition($search);
        }

        $res = $paginator->paginate(
            $res,
            $request->query->get('page', 1),
            30
        );

        return $this->render('site/search/results.html.twig', [
            'resultList' => $res
        ]);
    }
}
