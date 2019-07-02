<?php

namespace App\Controller;

use App\Entity\MobilHome;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    /**
     * @Route("/mobilhomes/{slugMh}/reservation", name="reservation_create")
     * @IsGranted("ROLE_USER")
     */
    public function reservation(MobilHome $mobilhome, Request $request, ObjectManager $manager)
    {
        $booking = new Reservation();
        $form = $this->createForm(ReservationType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $booking->setClient($user)
                    ->setAnnonce($mobilhome);

            // Si les dates ne sont pas disponibles, message d'erreur
            if (!$booking->isBookableDate()) {
                $this->addFlash(
                    'warning',
                    "Les dates que vous avez choisi ne peuvent pas être réservées, elles sont déjà prises. Choisissez un autre Mobil Home."
                );
            } else {
                // Sinon enregistrement et redirection
                $manager->persist($booking);
                $manager->flush();

                return $this->redirectToRoute('reservation_show', ['id' =>$booking->getId(),'withAlert' => true]);
            }
        }

        return $this->render('reservation/reservation.html.twig', [
            'mobilhome' => $mobilhome,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher la page d'une réservation
     * 
     * @Route("/reservation/{id}", name="reservation_show")
     *
     * @param Reservation $booking
     * @return Response
     */
    public function show(Reservation $booking) {
        return $this->render('reservation/show.html.twig', [
            'booking' => $booking
        ]);
    }
}
