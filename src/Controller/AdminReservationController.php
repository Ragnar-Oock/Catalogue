<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
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
        $validated = $rr->findByValidationStatus(true);
        $validatedCount = count($validated);
        $validated = array_slice($validated, 0, 15);
        // $validated = $paginator->paginate(
        //     $validated,
        //     $request->query->get('vpage', 1),
        //     1,
        //     ['pageParameterName' => 'vpage']
        // );
        $rejected = $rr->findByValidationStatus(false);
        $rejectedCount = count($rejected);
        $rejected = array_slice($rejected, 0, 15);
        // $rejected = $paginator->paginate(
        //     $rejected,
        //     $request->query->get('rpage', 1),
        //     1,
        //     ['pageParameterName' => 'rpage']
        // );
        $pending = $rr->findPendingValidation();
        $pendingCount = count($pending);
        $pending = array_slice($pending, 0, 15);
        // $pending = $paginator->paginate(
        //     $pending,
        //     $request->query->get('ppage', 1),
        //     1,
        //     ['pageParameterName' => 'ppage']
        // );

        return $this->render('admin/reservation/index.html.twig', [
            'validated' => $validated,
            'validatedCount' => $validatedCount,
            'rejected' => $rejected,
            'rejectedCount' => $rejectedCount,
            'pending' => $pending,
            'pendingCount' => $pendingCount
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
            1
        );

        return $this->render('admin/reservation/listValidated.html.twig', [
            'validated' => $validated,
            'validatedCount' => $validatedCount
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
            1
        );

        return $this->render('admin/reservation/listRejected.html.twig', [
            'rejected' => $rejected,
            'rejectedCount' => $rejectedCount
        ]);
    }


    /**
     * @Route("/liste/en-attente", name="admin_reservation_pending")
     */
    public function listPending(ReservationRepository $rr, Request $request, PaginatorInterface $paginator)
    {
        $pending = $rr->findPendingValidation();

        $pending = $rr->findByValidationStatus(false);
        $pendingCount = count($pending);
        $pending = $paginator->paginate(
            $pending,
            $request->query->get('page', 1),
            1
        );

        return $this->render('admin/reservation/listePending.html.twig', [
            'pen$pending' => $pending,
            'pen$pendingCount' => $pendingCount
        ]);
    }

    /**
     * @Route("/validate/{reservation}", name="admin_reservation_validate", methods={"POST"})
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
     * @Route("/reject/{reservation}", name="admin_reservation_reject", methods={"POST"})
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
