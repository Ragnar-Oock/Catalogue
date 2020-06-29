<?php

namespace App\Controller;

use App\Entity\Edition;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Form\SearchReservationType;
use App\Repository\EditionRepository;
use App\Repository\ReservationRepository;
use App\Service\ReservationHelper;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    /**
     * @Route("/profil/mes-reservations", name="reservation_index", methods={"GET"})
     */
    public function index(ReservationRepository $rr, PaginatorInterface $paginator, Request $request): Response
    {
        $form = $this->createForm(SearchReservationType::class, null, [
            'action' => $this->generateUrl('reservation_search'),
            'method' => 'GET',
        ]);

        $reservations = $paginator->paginate(
            $rr->findByUser($this->getUser()),
            $request->query->get('page', 1),
            15
        );

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/reserver/nouvelle/{edition}", name="reservation_new", methods={"GET","POST"})
     */
    public function new(Request $request, Edition $edition, EditionRepository $er, ReservationHelper $rh, ReservationRepository $rr): Response
    {
        // check if user is logged
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // create new reservation
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        $reservation->setEdition($edition);
        $reservation->setUser($this->getUser());

        $disabledDays = $rh->getUnavailableDays($reservation, $rr);

        
        if ($form->isSubmitted() && $form->isValid()) {
            $begining = $form->getData()->getBeginingAt();
            $end = $form->getData()->getEndingAt();

            if (
                $begining < $end 
                && $begining->diff($end)->days <= $_ENV['APP_MAX_RESERVATION_TIME']
                && $begining->diff(new \DateTime('NOW'))->days <= $_ENV['APP_MAX_RESERVATION_LENGTH']
                && $er->isAvailable($form->getData()->getEdition(), $begining, $end)
            ) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($reservation);
                $entityManager->flush();

                $this->addFlash('success', 'Votre demade de reservation a bien été enregistrée, elle doit etre maintenant accéptée par un administrateur
                ');

                return $this->redirectToRoute('reservation_index');
            }
            else {
                $this->addFlash('danger', 'Le document que vous essayez de reserver n\'est pas disponible sur la période choisie, ou la période choisie c\'est pas valide.');
                return $this->render('reservation/edit.html.twig', [
                    'reservation' => $reservation,
                    'form' => $form->createView(),
                ]);
            }
        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
            'disabledDays' => $disabledDays,
            'maxReservationLength' => $_ENV['APP_MAX_RESERVATION_LENGTH'],
            'maxReservationTime' => $_ENV['APP_MAX_RESERVATION_TIME']
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
     * @Route("/reserver/{reservation}/modifier", name="reservation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reservation $reservation, EditionRepository $er, ReservationHelper $rh, ReservationRepository $rr): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        $disabledDays = $rh->getUnavailableDays($reservation, $rr);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $begining = $form->getData()->getBeginingAt();
            $end = $form->getData()->getEndingAt();

            if (
                $begining < $end 
                && $begining->diff($end)->days <= $_ENV['APP_MAX_RESERVATION_LENGTH']
                && $end->diff(new \DateTime('NOW'))->days <= $_ENV['APP_MAX_RESERVATION_TIME']
                && $er->isAvailable($form->getData()->getEdition(), $begining, $end, $reservation)
            ) {
                $this->getDoctrine()->getManager()->flush();
    
                return $this->redirectToRoute('reservation_index');
            }
            else {
                $this->addFlash('danger', 'Le document que vous essayez de reserver n\'est pas disponible sur la période choisie, ou la période choisie c\'est pas valide.');
                return $this->render('reservation/edit.html.twig', [
                    'reservation' => $reservation,
                    'form' => $form->createView(),
                    'disabledDays' => $disabledDays,
                    'maxReservationLength' => $_ENV['APP_MAX_RESERVATION_LENGTH'],
                    'maxReservationTime' => $_ENV['APP_MAX_RESERVATION_TIME']
                ]);
            }
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
            'disabledDays' => $disabledDays,
            'maxReservationLength' => $_ENV['APP_MAX_RESERVATION_LENGTH'],
            'maxReservationTime' => $_ENV['APP_MAX_RESERVATION_TIME']
        ]);
    }


    /**
     * @Route("/profil/mes-reservations/rechercher", name="reservation_search")
     */
    public function search(ReservationRepository $rr, PaginatorInterface $paginator, Request $request): Response
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
        
        $values = [
            'submitedAtBegining' => $submitedAtBegining,
            'submitedAtEnd' => $submitedAtEnd,
            'rangeBegining' => $rangeBegining,
            'rangeEnd' => $rangeEnd,
            'canceled' => $canceled,
            'validated' => $validated,
            'pending' => $pending
        ];

        $form = $this->createForm(SearchReservationType::class, $values, [
            'action' => $this->generateUrl('reservation_search'),
            'method' => 'GET',
        ]);
        // $form->handleRequest($request);

        // if either of the field is filled execute the search
        if ($submitedAtBegining || $submitedAtEnd || $rangeBegining || $rangeEnd || $canceled || $validated || $pending) {
                
            $reservations = $rr->search(
                $submitedAtBegining,
                $submitedAtEnd,
                $rangeBegining,
                $rangeEnd,
                $canceled,
                $validated,
                $pending,
                null,
                $this->getUser()
            );
    
            $reservations = $paginator->paginate(
                $reservations,
                $request->query->get('page', 1),
                15
            );
    
            return $this->render('reservation/index.html.twig', [
                'reservations' => $reservations,
                'form' => $form->createView()
            ]);
        }
        else {
            return $this->redirectToRoute('reservation_index');
        }
    }
}
