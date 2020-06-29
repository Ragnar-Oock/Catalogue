<?php

namespace App\Controller;

use App\Entity\Edition;
use App\Entity\Reservation;
use App\Entity\User;
use App\Form\ReservationCommentaireType;
use App\Form\AdminSearchReservationType;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/reservation")
 */

class AdminReservationController extends AbstractController
{
    /**
     * @Route("/", name="admin_reservation_index")
     */
    public function index(ReservationRepository $rr, Request $request, PaginatorInterface $paginator)
    {
        // show up to 5 items of each and then a link to the full list
        $validated = $rr->findByValidationStatus(true);
        $validatedCount = count($validated);
        $validated = array_slice($validated, 0, 5);

        $rejected = $rr->findByValidationStatus(false);
        $rejectedCount = count($rejected);
        $rejected = array_slice($rejected, 0, 5);

        $pending = $rr->findPendingValidation();
        $pendingCount = count($pending);
        $pending = array_slice($pending, 0, 5);

        $canceled = $rr->findCanceled();
        $canceledCount = count($canceled);
        $canceled = array_slice($canceled, 0, 5);

        $form = $this->createForm(AdminSearchReservationType::class, null, [
            'action' => $this->generateUrl('admin_reservation_search'),
            'method' => 'GET',
        ]);

        return $this->render('admin/reservation/index.html.twig', [
            'validated' => $validated,
            'validatedCount' => $validatedCount,
            'rejected' => $rejected,
            'rejectedCount' => $rejectedCount,
            'pending' => $pending,
            'pendingCount' => $pendingCount,
            'canceled' => $canceled,
            'canceledCount' => $canceledCount,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/liste/valide", name="admin_reservation_validated")
     */
    public function listValidated(ReservationRepository $rr, Request $request, PaginatorInterface $paginator)
    {
        $validated = $rr->findByValidationStatus(true);
        $validatedCount = count($validated);
        $validated = $paginator->paginate(
            $validated,
            $request->query->get('page', 1),
            15
        );

        return $this->render('admin/reservation/listReservation.html.twig', [
            'reservations' => $validated,
            'reservationCount' => $validatedCount,
            'title' => 'Réservations validées'
        ]);
    }

    
    /**
     * @Route("/liste/rejete", name="admin_reservation_rejected")
     */
    public function listRejected(ReservationRepository $rr, Request $request, PaginatorInterface $paginator)
    {
        $rejected = $rr->findByValidationStatus(false);
        $rejectedCount = count($rejected);
        $rejected = $paginator->paginate(
            $rejected,
            $request->query->get('page', 1),
            15
        );

        return $this->render('admin/reservation/listReservation.html.twig', [
            'reservations' => $rejected,
            'reservationCount' => $rejectedCount,
            'title' => 'Réservations rejetées'
        ]);
    }


    /**
     * @Route("/liste/en-attente", name="admin_reservation_pending")
     */
    public function listPending(ReservationRepository $rr, Request $request, PaginatorInterface $paginator)
    {
        $pending = $rr->findPendingValidation();
        $pendingCount = count($pending);
        $pending = $paginator->paginate(
            $pending,
            $request->query->get('page', 1),
            15
        );

        return $this->render('admin/reservation/listReservation.html.twig', [
            'reservations' => $pending,
            'reservationCount' => $pendingCount,
            'title' => 'Réservations rejetées'
        ]);
    }

    /**
     * @Route("/liste/annulee", name="admin_reservation_canceled")
     */
    public function listCanceled(ReservationRepository $rr, Request $request, PaginatorInterface $paginator)
    {
        $Canceled = $rr->findCanceled();
        $CanceledCount = count($Canceled);
        $Canceled = $paginator->paginate(
            $Canceled,
            $request->query->get('page', 1),
            15
        );

        return $this->render('admin/reservation/listReservation.html.twig', [
            'reservations' => $Canceled,
            'reservationCount' => $CanceledCount,
            'title' => 'Réservations rejetées'
        ]);
    }

    /**
     * @Route("/{reservation}/validees", name="admin_reservation_validate", methods={"POST"})
     */
    public function validate(Reservation $reservation, Request $request)
    {
        if ($this->isCsrfTokenValid('valider' . $reservation->getId(), $request->request->get('_token'))) {
            $reservation->setValidated(true);
            $reservation->setValidatedAt(new \DateTime());

            $this->getDoctrine()->getManager()->flush();
        } else {
            $this->addFlash('danger', 'Tokken CSRF invalide, veullez réessayer');
        }
        return $this->redirectToRoute('admin_reservation_index');
    }

    /**
     * @Route("/{reservation}/rejetees", name="admin_reservation_reject", methods={"POST"})
     */
    public function reject(Reservation $reservation, Request $request)
    {
        if ($this->isCsrfTokenValid('reject' . $reservation->getId(), $request->request->get('_token'))) {
            $reservation->setValidated(false);
            $reservation->setValidatedAt(new \DateTime());

            $this->getDoctrine()->getManager()->flush();
        } else {
            $this->addFlash('danger', 'Tokken CSRF invalide, veullez réessayer');
        }
        return $this->redirectToRoute('admin_reservation_index');
    }

    /**
     * @Route("/{reservation}/ajouter-un-commentaire", name="admin_reservation_add_comment")
     */
    public function addCommentaire(Reservation $reservation, Request $request)
    {
        $form = $this->createForm(ReservationCommentaireType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('admin_reservation_index');
        }

        return $this->render('admin/reservation/commentaire.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/rechercher", name="admin_reservation_search")
     */
    public function searchResults(ReservationRepository $rr, PaginatorInterface $paginator, Request $request)
    {
        $formValues = $request->query->get('search_reservation');
        $format = 'd/m/Y H:i';

        $submitedAtBegining = $formValues['submitedAtBegining'] != null ? \DateTime::createFromFormat ($format , $formValues['submitedAtBegining']) : null;
        if ($submitedAtBegining === false) {
            $submitedAtBegining = null;
        }
        $submitedAtEnd = $formValues['submitedAtEnd'] != null ? \DateTime::createFromFormat ($format , $formValues['submitedAtEnd']) : null;
        if ($submitedAtEnd === false) {
            $submitedAtEnd = null;
        }
        $rangeBegining = $formValues['rangeBegining'] != null ? \DateTime::createFromFormat ($format , $formValues['rangeBegining']) : null;
        if ($rangeBegining === false) {
            $rangeBegining = null;
        }
        $rangeEnd = $formValues['rangeEnd'] != null ? \DateTime::createFromFormat ($format , $formValues['rangeEnd']) : null;
        if ($rangeEnd === false) {
            $rangeEnd = null;
        }
        $canceled = isset($formValues['canceled']) && $formValues['canceled'];
        $validated = isset($formValues['validated']) && $formValues['validated'];
        $pending = isset($formValues['pending']) && $formValues['pending'];
        $haveCommentaire = isset($formValues['haveCommentaire']) && $formValues['haveCommentaire'];
        $user = $formValues['user'] != null ? $formValues['user'] : null;
        
        $values = [
            'submitedAtBegining' => $submitedAtBegining,
            'submitedAtEnd' => $submitedAtEnd,
            'rangeBegining' => $rangeBegining,
            'rangeEnd' => $rangeEnd,
            'canceled' => $canceled,
            'validated' => $validated,
            'pending' => $pending,
            'haveCommentaire' => $haveCommentaire,
            'user' => $user
        ];

        $form = $this->createForm(AdminSearchReservationType::class, $values, [
            'action' => $this->generateUrl('admin_reservation_search'),
            'method' => 'GET',
        ]);
        $form->handleRequest($request);

        // if either of the field is filled execute the search
        if ($submitedAtBegining || $submitedAtEnd || $rangeBegining || $rangeEnd || $canceled || $validated || $haveCommentaire || $user) {
                
            $reservations = $rr->search(
                $submitedAtBegining,
                $submitedAtEnd,
                $rangeBegining,
                $rangeEnd,
                $canceled,
                $validated,
                $pending,
                $haveCommentaire,
                $user
            );
    
            $reservations = $paginator->paginate(
                $reservations,
                $request->query->get('page', 1),
                15
            );
    
            return $this->render('admin/reservation/search_results.html.twig', [
                'reservations' => $reservations,
                'form' => $form->createView()
            ]);
        }
        else {
            return $this->redirectToRoute('admin_reservation_index');
        }

    }

    /**
     * @Route("/edition/{edition}", name="admin_reservation_edition")
     */
    public function showReservationOfEdition(Edition $edition, ReservationRepository $rr, PaginatorInterface $paginator, Request $request)
    {
        $reservations = $rr->findBy(['edition'=>$edition], ['submitedAt' => 'DESC']);
        $reservationsCount = count($reservations);
        $reservations = $paginator->paginate(
            $reservations,
            $request->query->get('page', 1),
            15
        );

        return $this->render('admin/reservation/listReservation.html.twig', [
            'reservations' => $reservations,
            'reservationCount' => $reservationsCount,
            'title' => 'Réservations de '.$edition->getDocument()->getTitle()
        ]);
    }

    /**
     * @Route("/utilisateur/{user}", name="admin_user_s_reservations")
     */
    public function listUsers(User $user, ReservationRepository $rr, Request $request, PaginatorInterface $paginator)
    {
        $usersReservations = $rr->findBy(['user' => $user], ['submitedAt' => 'DESC']);
        $usersReservations = $paginator->paginate(
            $usersReservations,
            $request->query->get('page', 1),
            15
        );

        $title = 'Réservations de l\'utilisateur ';
        if (!empty($user->getFirstname()) || !empty($user->getLastname())) {
            $title .= $user->getFirstname() . ' ' . $user->getLastname();
        }
        elseif (!empty($user->getEmail())) {
            $title .= $user->getEmail();
        }
        else {
            $title = 'Réservations de l\'utilisateur d\'id ' . $user->getId() . ' (compte supprimé)';
        }

        return $this->render('admin/reservation/listReservation.html.twig', [
            'reservations' => $usersReservations,
            'title' => $title
        ]);
    }

    // /**
    //  * @Route("/{id}", name="author_delete", methods={"DELETE"})
    //  */
    // public function delete(Request $request, Author $author): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$author->getId(), $request->request->get('_token'))) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->remove($author);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('author_index');
    // }
}
