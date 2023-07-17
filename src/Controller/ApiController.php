<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    // #[Route('/api', name: 'app_api')]
    // public function index(): Response
    // {
    //     return $this->render('api/index.html.twig', [
    //         'controller_name' => 'ApiController',
    //     ]);
    // }

    
    #[Route('/api/reservation', name: 'api_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository)
    {
        
        // processus de sérialisation = normalisation + encodage
        
        // VERSION DÉTAILLÉE
        
        // $reservations = $reservationRepository->findAll();
        
        // normalisation = transforme les objets complexes en tableaux associatifs simples
        // récupère uniquement les informations du groupe 'reservation:read'
        // $reservationsNormalises = $normalizer->normalize($reservations, null, ['groups' => 'reservation:read']);
        
        // transforme les tableaux associtafis en format JSON
        // encodage = transforme les tableaux associatifs simples en du texte
        // $json = json_encode($reservationsNormalises);
        
        // $response = new Response($json, 200, [
            // l'entête "Content-Type" explique au client (navigateur, Postman...) que la réponse envoyée contient du JSON
        //     "Content-Type" => "application/json"
        // ]);
        

        // VERSION AMÉLIORÉE
        
        // $json = $serializer->serialize($reservations, 'json', ['groups' => 'reservation:read']);
        
        // 'true' précise que '$json' est déjà du json et n'a pas besoin de faire la transformation
        // $response = new JsonResponse($json, 200, [], true);
        
        
        // "$this->json()" = processus de sérialisation
        // la fonction json() est héritée de l'AbstractController
        return $this->json($reservationRepository->findAll(), 200, [], ['groups' => 'reservation:read']);
        
    }
    
    #[Route('/api/reservation', name: 'api_reservation_store', methods: ['POST'])]
    public function store(Request $request, SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $donnees = $request->getContent();
        
        $reservation = $serializer->deserialize($donnees, Reservation::class, 'json');
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($reservation);
        $em->flush();
        
        return $this->json($reservation, 201, [], ['groups' => 'reservation:read']);
    }
    
    #[Route('/api/{id}/edit', name: 'api_event_edit', methods: ['PUT'])]
    public function majEvent(?Reservation $reservation, Request $request)
    {
        // On récupère les données
        $donnees = json_decode($request->getContent());
    
        if(
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->backgroundColor) && !empty($donnees->backgroundColor) &&
            isset($donnees->borderColor) && !empty($donnees->borderColor) &&
            isset($donnees->textColor) && !empty($donnees->textColor)
        ){
        // Les données sont complètes
        // On initialise un code
        $code = 200;
    
        // On vérifie si l'id existe
        if(!$reservation){
            // On instancie un rendez-vous
            $reservation = new Calendar;
    
            // On change le code
            $code = 201;
        }
    
        // On hydrate l'objet avec les données
        $reservation->setTitle($donnees->title);
        $reservation->setDescription($donnees->description);
        $reservation->setStart(new DateTime($donnees->start));
        if($donnees->allDay){
            $reservation->setEnd(new DateTime($donnees->start));
        }else{
            $reservation->setEnd(new DateTime($donnees->end));
        }
        $reservation->setAllDay($donnees->allDay);
        $reservation->setBackgroundColor($donnees->backgroundColor);
        $reservation->setBorderColor($donnees->borderColor);
        $reservation->setTextColor($donnees->textColor);

        $em = $this->getDoctrine()->getManager();
        $em->persist($reservation);
        $em->flush();
    
        // On retourne le code
        return new Response('Ok', $code);
    }else{
        // Les données sont incomplètes
        return new Response('Données incomplètes', 404);
    }
    
    
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
}