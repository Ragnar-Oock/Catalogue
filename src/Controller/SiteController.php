<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Collec;
use App\Entity\Editor;
use App\Entity\Edition;
use App\Form\SearchFormType;
use App\Repository\AuthorRepository;
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
    public function index(AuthorRepository $ar)
    {
        return $this->render('site/index.html.twig', [
            'controller_name' => 'SiteController',
            'randomAuthors'=>$ar->pickAtRandom(15)
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

    /**
     * @Route("/explorer/auteur/{author}", name="explore_author")
     */
    public function showAuthor(Request $request, PaginatorInterface $paginator, Author $author)
    {
        if ($author != null) {
            $bibliography = $author->getParticipations();
            $bibliography = $paginator->paginate(
                $bibliography,
                $request->query->get('page', 1),
                15
            );

            return $this->render('site/explore/author.html.twig', [
                'bibliography' => $bibliography,
                'author' => $author
            ]);
        }
    }
    
    /**
     * @Route("/explorer/edition/{edition}", name="explore_edition")
     */
    public function showEdition(Request $request, PaginatorInterface $paginator, Edition $edition)
    {
        if ($edition != null) {
            
            $authors = $edition->getWriters();
            $authors = $paginator->paginate(
                $authors,
                $request->query->get('page', 1),
                15
            );

            return $this->render('site/explore/edition.html.twig', [
                'authors' => $authors,
                'edition' => $edition
            ]);
        }
    }

    /**
     * @Route("/explorer/editeur/{editor}", name="explore_editor")
     */
    public function showEditor(Request $request, PaginatorInterface $paginator, Editor $editor)
    {
        if ($editor != null) {

            $editions = $paginator->paginate(
                $editor->getEditions(),
                $request->query->get('page', 1),
                15
            );

            return $this->render('site/explore/editor.html.twig', [
                'editor' => $editor,
                'editions' => $editions
            ]);
        }
        throw $this->createNotFoundException('Cet éditeur n\'existe pas');
    }

    /**
     * @Route("/explorer/collection/{collection}", name="explore_collection")
     */
    public function showCollection(Request $request, PaginatorInterface $paginator, Collec $collection)
    {
        if ($collection != null) {

            $editions = $paginator->paginate(
                $collection->getEditions(),
                $request->query->get('page', 1),
                15
            );

            return $this->render('site/explore/collection.html.twig', [
                'collection' => $collection,
                'editions' => $editions
            ]);
        }
        throw $this->createNotFoundException('Cette collection n\'existe pas');
    }

    /**
     * @Route("/explorer/tous-les-auteurs", name="explore_all_authors")
     */
    public function showAllAuthors(Request $request, PaginatorInterface $paginator, AuthorRepository $ar)
    {
        $authors = $ar->findAllInOrder();
        $authors = $paginator->paginate(
            $authors,
            $request->query->get('page', 1),
            60
        );

        return $this->render('site/explore/allAuthors.html.twig', [
            'authorsList' => $authors
        ]);
    }

    /**
     * @Route("/mentions-legales", name="mentions_legales")
     */
    public function mentionsLegales()
    {
        return $this->render('site/legal.html.twig');
    }
}
