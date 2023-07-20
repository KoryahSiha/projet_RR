<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function home(ReservationRepository $reservation)
    {
        $reservations = $reservation->findAll();
        // dd($reservations);
        $events = [];

        foreach($reservations as $reservation) {
            $events[] = [
                'id' => $reservation->getId(),
                'start' => $reservation->getStart()->format('Y-m-d H:i:s'),
                'end' => $reservation->getEnd()->format('Y-m-d H:i:s'),
                'title' => $reservation->getTitle(),
                'description' => $reservation->getDescription(),
                'typeReservation' => $reservation->getTypeReservation()->getNom(),
                'gestionnaireSalle' => $reservation->getGestionnaireSalle()->__toString(),
                'participantNumber' => $reservation->getParticipantNumber(),
                'duration' => $reservation->getDuration(),
                'backgroundColor' => $reservation->getBackgroundColor(),
                'borderColor' => $reservation->getBorderColor(),
                'textColor' => $reservation->getTextColor(),
                'allDay' => $reservation->getAllDay(),
                'salle' => $reservation->getSalle()->getNom(),
                'url' => $reservation->getUrl(),
            ];
        }

        $data = json_encode($events);

        return $this->render('pages/home.html.twig', compact('data'));
    }

}
