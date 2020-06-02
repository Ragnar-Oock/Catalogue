<?php

namespace App\Controller;

use App\Entity\Edition;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    /**
     * @Route("/profil/mes-reservations", name="reservation_index", methods={"GET"})
     */
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findByUser($this->getUser()),
        ]);
    }

    /**
     * @Route("/reserver/nouvelle/{edition}", name="reservation_new", methods={"GET","POST"})
     */
    public function new(Request $request, Edition $edition): Response
    {
        // check if user is logged
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // create new reservation
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        $reservation->setEdition($edition);
        $reservation->setUser($this->getUser());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/reserver/anuler/{reservation}", name="reservation_cancel")
     */
    public function cancel(Reservation $reservation, Request $request)
    {
        if ($this->isCsrfTokenValid('cancel' . $reservation->getId(), $request->request->get('_token'))) {
            $reservation->setCanceled(true);
            $reservation->setLastEditedAt(new \DateTime());

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('succes', 'Reservation annulée');
        }
        else {
            $this->addFlash('danger', 'Tokken CSRF invalide, veullez réessayer ' . 'cancel' . $reservation->getId());
        }

        $referer = $request->headers->get('referer');

        if ($referer && is_string($referer)) {
            return $this->redirect($referer);
        } elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_reservation_index');
        } else {
            return $this->redirectToRoute("reservation_index");
        }
    }

    /**
     * @Route("/reserver/{id}", name="reservation_show", methods={"GET"})
     */
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * @Route("/reserver/{id}/modifier", name="reservation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reservation $reservation): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $begining = $form->getData()->getBeginingAt();
            $end = $form->getData()->getEndingAt();

            if ($begining < $end && $begining->diff($end)->days <= 60) {
                $this->getDoctrine()->getManager()->flush();
    
                return $this->redirectToRoute('reservation_index');
            }
            else {
                $this->addFlash('danger', 'nope');
                return $this->render('reservation/edit.html.twig', [
                    'reservation' => $reservation,
                    'form' => $form->createView(),
                ]);
            }

        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    public function confineDates($begining, $end, $length)
    {
        dd($begining < $end && $begining->diff($end), $length);
        // if ($begining < $end && $begining->diff($end) > 60) {
        //     # code...
        // }
    }
}