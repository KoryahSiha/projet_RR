<?php

namespace App\Controller;

use App\Entity\GestionnaireSalle;
use App\Entity\Reservation;
use App\Entity\Salle;
use App\Entity\TypeReservation;
use App\Repository\ReservationRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
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
    
    // #[Route('/api/reservation', name: 'api_reservation_store', methods: ['POST'])]
    // public function store(Request $request, SerializerInterface $serializer, EntityManagerInterface $em)
    // {
    //     $donnees = $request->getContent();

    //     try {
    //         $reservation = $serializer->deserialize($donnees, Reservation::class, 'json');

    //         $reservation->setStart(new \DateTime());

    //         // Vérifier si la salle existe déjà en base de données
    //         $salle = $em->getRepository(Salle::class)->findOneBy(['nom' => $reservation->getSalle()->getNom()]);
    //         if (!$salle) {
    //             // Si la salle n'existe pas, créez une nouvelle instance et associez-la à la réservation
    //             $salle = new Salle();
    //             $salle->setNom($reservation->getSalle()->getNom());
    //         }
    //         $reservation->setSalle($salle);

    //         // Vérifier si le type de réservation existe déjà en base de données
    //         $typeReservation = $em->getRepository(TypeReservation::class)->findOneBy(['nom' => $reservation->getTypeReservation()->getNom()]);
    //         if (!$typeReservation) {
    //             // Si le type de réservation n'existe pas, créez une nouvelle instance et associez-la à la réservation
    //             $typeReservation = new TypeReservation();
    //             $typeReservation->setNom($reservation->getTypeReservation()->getNom());
    //         }
    //         $reservation->setTypeReservation($typeReservation);

    //         // Vérifier si le gestionnaire de salle existe déjà en base de données
    //         $gestionnaireSalleId = $reservation->getGestionnaireSalle()->getUser()->getId();
    //         $gestionnaireSalle = $em->getRepository(GestionnaireSalle::class)->find($gestionnaireSalleId);
    //         if (!$gestionnaireSalle) {
    //             // Si le gestionnaire de salle n'existe pas, lancez une exception ou renvoyez une erreur appropriée
    //             throw new \Exception("Le gestionnaire de salle spécifié n'existe pas.");
    //         }
    //         // Attacher l'entité GestionnaireSalle existante à l'EntityManager
    //         $reservation->setGestionnaireSalle($gestionnaireSalle);

    //         $em->persist($reservation);
    //         $em->flush();
            
    //         return $this->json($reservation, 201, [], ['groups' => 'reservation:read']);
    //     } catch(NotEncodableValueException $e) {
    //         return $this->json([
    //             'status' =>400,
    //             'message' => $e->getMessage()
    //         ], 400);
    //     }
    // }
    
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
            isset($donnees->textColor) && !empty($donnees->textColor) &&
            isset($donnees->salle) && !empty($donnees->salle) &&
            isset($donnees->typeReservation) && !empty($donnees->typeReservation) &&
            isset($donnees->gestionnaireSalle) && !empty($donnees->gestionnaireSalle)
        ){
            // Les données sont complètes
            // On initialise un code
            $code = 200;
        
            // On vérifie si l'id existe
            if(!$reservation){
                // On instancie une réservation
                $reservation = new Calendar;
        
            // On change le code
                $code = 201;
            }
        
            // // On hydrate (remplir ou initialiser les propriétés d'un objet à partir des données fournies)
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

            try {
                $salleRepository = $this->getDoctrine()->getRepository(Salle::class);
                $salle = $salleRepository->find($donnees->salle);
            
                if (!$salle) {
                    throw new \RuntimeException("Salle introuvable : " . $donnees->salle);
                }        
                // Hydratation de l'objet Reservation avec la salle associée
                $reservation->setSalle($salle);


                $typeReservationRepository = $this->getDoctrine()->getRepository(TypeReservation::class);
                $typeReservation = $typeReservationRepository->find($donnees->typeReservation);

                if (!$typeReservation) {
                    throw new \RuntimeException("Type de réservation introuvable : " . $donnees->typeReservation);
                }
                // Hydratation de l'objet Reservation avec le type de réservation associé
                $reservation->setTypeReservation($typeReservation);


                $gestionnaireSalleRepository = $this->getDoctrine()->getRepository(GestionnaireSalle::class);
                $gestionnaireSalle = $gestionnaireSalleRepository->find($donnees->gestionnaireSalle);

                if (!$gestionnaireSalle) {
                    throw new \RuntimeException("Gestionnaire de salle introuvable : " . $donnees->gestionnaireSalle);
                }
                // Hydratation de l'objet Reservation avec le gestionnaire de salle associé
                $reservation->setGestionnaireSalle($gestionnaireSalle);
            
            } catch (Exception $e) {
                // Gérer l'exception ici
                echo "Erreur : " . $e->getMessage();
            }
            $reservation->setParticipantNumber($donnees->participantNumber);
            $reservation->setUrl($donnees->url);
            $reservation->setDeposit($donnees->deposit);
            $reservation->setPaid($donnees->paid);

            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();
        
            // // On retourne le code
            return new Response('Ok', $code);
            
        } else{
            // Les données sont incomplètes
            return new Response('Données incomplètes', 404);
        }
    
    
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
}