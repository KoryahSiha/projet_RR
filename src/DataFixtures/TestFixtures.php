<?php

namespace App\DataFixtures;

use App\Entity\Domaine;
use App\Entity\GestionnaireSalle;
use App\Entity\Reservation;
use App\Entity\Salle;
use App\Entity\TypeReservation;
use App\Entity\User;
use \DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestFixtures extends Fixture
{
    private $doctrine;
    private $faker;
    private $hasher;
    private $manager;


    public function __construct(ManagerRegistry $doctrine, UserPasswordHasherInterface $hasher)
    {
        $this->doctrine = $doctrine;
        $this->faker = FakerFactory::create('fr_FR');
        $this->hasher = $hasher;

    }

    // la fonction 'load' est utilisée pour charger des données initiales (statiques et/ou dynamiques) dnas la BDD en appelant d'autres d'autres méthodes de la même classe pour chaque type d'objet
    // ObjectManager permet d'intéragir avec la BDD, ici charger les données

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        // méthodes qui permettent de charger les données dans la BDD
        $this->loadUsers();
        $this->loadGestionnaireSalles();
        $this->loadDomaines();
        $this->loadSalles();
        $this->loadTypeReservations();
        $this->loadReservations();
    }

    public function loadUsers(): void
    {
        // création des données statiques
        // $datas = [] est un tableau de données contenant des informations pour créer des utilisateurs.
        // chaque élément du tableau est un tableau associatif avec les clés 'email', 'roles', 'password' et 'enabled'
        $datas = [
            [
                'email' => 'admin@example.com',
                'roles' => ['ROLE_ADMIN'],
                'password' => '123',
                'enabled' => true
            ],
            [
                'email' => 'foo.foo@example.com',
                'roles' => ['ROLE_USER'],
                'password' => '123',
                'enabled' => true
            ],
            [
                'email' => 'bar.bar@example.com',
                'roles' => ['ROLE_GESTIONNAIRESAL'],
                'password' => '123',
                'enabled' => true
            ],
            [
                'email' => 'baz.baz@example.com',
                'roles' => ['ROLE_GESTIONNAIREDOM'],
                'password' => '123',
                'enabled' => true
            ],
        ];

        // boucle qui parcourt le tableau de données $datas
        // pour chaque élément du tableau, il exécute le code entre les accolades
        foreach ($datas as $data) {
            // création d'un nouvel objet
            $user = new User();

            // configuration de l'email de l'utilisateur avec la valeur stockée dans le tableau $datas
            $user->setEmail($data['email']);
            // configuration du rôle de l'utilisateur avec la valeur stockée dans le tableau $datas
            $user->setRoles($data['roles']);
            // utilisation d'une fonction de hachage pour convertir le mot de passe stocké dans le tableau $datas en une valeur sécurisée pour stockage en BDD
            $password = $this->hasher->hashPassword($user, $data['password']);
            // configuration du mot de passe de l'utilisateur avec la valeur hachée
            $user->setPassword($password);
            // configuration du compte de l'utilisateur avec la valeur stockée dans le tableau $datas
            $user->setEnabled($data['enabled']);

            // demande d'enregistrement de l'objet (ajoute l'utilisateur créé dans l'EntityManager de Doctrine, qui se chargera de les enregistrer en base de données plus tard)
            $this->manager->persist($user);
        }

        // création des données dynamiques
        // création de 100 users
        for ($i = 0; $i < 100; $i++) {
            $user = new User();

            $user->setEmail($this->faker->email());
            $user->setRoles(['ROLE_USER']);
            // utilisation d'une fonction de hachage pour convertir le mot de passe '123' en une valeur sécurisée pour stockage en BDD
            $password = $this->hasher->hashPassword($user, '123');
            // configuration du mot de passe de l'utilisateur avec la valeur hachée de '123'
            $user->setPassword($password);
            $user->setEnabled($this->faker->boolean());

            $this->manager->persist($user);
        }

        // exécution des requêtes SQL pour insérer les nouveaux enregistrements dans la BDD
        $this->manager->flush();
    }

    public function loadGestionnaireSalles(): void
    {
        $repository = $this->manager->getRepository(User::class);
        $users = $repository->findAll();

        $datas = [
            [
                'nom' => 'Martin',
                'prenom' => 'Leo',
                'user' => $users[1],
            ],
            [
                'nom' => 'Dubois',
                'prenom' => 'Alice',
                'user' => $users[2],
            ],
        ];

        foreach ($datas as $data) {
            $gestionnaireSalle = new GestionnaireSalle();

            $gestionnaireSalle->setNom($data['nom']);
            $gestionnaireSalle->setPrenom($data['prenom']);
            $gestionnaireSalle->setUser($data['user']);

            $this->manager->persist($gestionnaireSalle);
        }

        for ($i = 0; $i < 3; $i++) {
            $gestionnaireSalle = new GestionnaireSalle();
            
            $gestionnaireSalle->setNom($this->faker->lastname());
            $gestionnaireSalle->setPrenom($this->faker->firstname());
            $gestionnaireSalle->setUser($this->faker->randomElement($users));

            $this->manager->persist($gestionnaireSalle);
        }

        $this->manager->flush();
    }

    public function loadDomaines(): void
    {
        // données statiques
        $datas = [
            [
                'nom' => 'Mairie',
            ],
            [
                'nom' => 'DRH',
            ],
        ];

        foreach ($datas as $data) {
            $domaine = new Domaine();

            $domaine->setNom($data['nom']);

            $this->manager->persist($domaine);
        }

        // données dynamiques
        for ($i = 0; $i < 5; $i++) {
            $domaine = new Domaine();

            $domaine->setNom(ucfirst($this->faker->words(2, true)));

            $this->manager->persist($domaine);
        }

        $this->manager->flush();
    }

    public function loadSalles(): void
    {
        // récupération du repository de la classe Domaine 
        $repository = $this->manager->getRepository(Domaine::class);
        // la variable $domaines récupère un tableau avec tous les domaines
        // cela équivaut à 'select * from domaine'
        $domaines = $repository->findAll();

        // données statiques
        $datas = [
            [
                'nom' => 'Bureau des permanences',
                'description' => 'Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Maecenas laoreet leo nisi, a aliquam lacus egestas mollis. Duis ac massa lectus. Aenean commodo blandit nisl, sit amet congue metus condimentum ut. Morbi ut volutpat mauris. Nunc in purus eros.',
                'personnes_max' => null,
                'domaine' => $domaines[0]
            ],
            [
                'nom' => 'Salle de la Mairie',
                'description' => 'Nam orci justo, sodales non posuere id, varius quis libero. Donec sollicitudin erat id tortor vestibulum, sit amet hendrerit dolor euismod.',
                'personnes_max' => 70,
                'domaine' => $domaines[0]
            ],
            [
                'nom' => 'Salle de formation',
                'description' => 'Etiam accumsan, orci eget pulvinar eleifend, leo nibh volutpat mauris, et semper ex ex at dolor. Nulla condimentum cursus sem vel malesuada. Aliquam sit amet libero a turpis venenatis maximus.',
                'personnes_max' => 11,
                'domaine' => $domaines[1]
            ],
            [
                'nom' => 'Salle des mariages',
                'description' => 'Cras quis fringilla nisi, id tempus neque. Phasellus pharetra finibus neque, a venenatis est tristique eu. Nam vulputate, magna in gravida rutrum, felis magna eleifend est, a maximus felis sem ut sapien.',
                'personnes_max' => null,
                'domaine' => $domaines[0]
            ],
        ];

        foreach ($datas as $data) {
            $salle = new Salle();

            $salle->setNom($data['nom']);
            $salle->setDescription($data['description']);
            $salle->setPersonnesMax($data['personnes_max']);
            $salle->setDomaine($data['domaine']);

            $this->manager->persist($salle);
        }

        for ($i = 0; $i < 15; $i++) {
            $salle = new Salle();

            $salle->setNom(ucfirst($this->faker->words(3, true)));
            $salle->setDescription($this->faker->optional($weight = 0.6)->text());
            $salle->setPersonnesMax($this->faker->optional($weight = 0.8)->numberBetween(0, 300));
            $salle->setDomaine($this->faker->randomElement($domaines));

            $this->manager->persist($salle);
        }

        $this->manager->flush();
    }

    public function loadTypeReservations(): void
    {
        // données statiques
        $datas = [
            [
                'nom' => 'Réservation gratuite',
                'description' => 'Vivamus dictum orci arcu, nec rutrum est rhoncus sit amet. Aenean accumsan turpis elit, vel hendrerit ante aliquam at. Nulla nec venenatis dolor.'
            ],
            [
                'nom' => 'Manifestation municipale',
                'description' => 'Donec eu faucibus eros, consectetur pulvinar justo. Vivamus dapibus augue ultrices nunc pretium consectetur. Duis id lacus quis ipsum imperdiet molestie vel at mauris.'
            ],
            [
                'nom' => 'Réservation payante de salle',
                'description' => 'Integer vestibulum ex id hendrerit imperdiet. Curabitur blandit, ex eget rutrum tempus, diam sapien sollicitudin ante, eu porta turpis mi ut sem.'
            ],
            [
                'nom' => 'Activités associations',
                'description' => null
            ],
            [
                'nom' => 'TAP',
                'description' => 'Praesent a sem magna. Aenean luctus orci quis erat malesuada accumsan. Nulla accumsan leo id leo elementum, non tincidunt eros auctor.'
            ],
        ];

        foreach ($datas as $data) {
            $typeReservation = new TypeReservation();

            $typeReservation->setNom($data['nom']);
            $typeReservation->setDescription($data['description']);

            $this->manager->persist($typeReservation);
        }

        // données dynamiques
        for ($i = 0; $i < 6; $i++) {
            $typeReservation = new TypeReservation();

            $typeReservation->setNom(ucfirst($this->faker->words(3, true)));
            $typeReservation->setDescription($this->faker->optional($weight = 0.6)->text());

            $this->manager->persist($typeReservation);
        }

        $this->manager->flush();
    }

    public function loadReservations(): void
    {
        $repository = $this->manager->getRepository(Salle::class);
        $salles = $repository->findAll();

        $repository = $this->manager->getRepository(TypeReservation::class);
        $typeReservations = $repository->findAll();

        $repository = $this->manager->getRepository(GestionnaireSalle::class);
        $gestionnaireSalles = $repository->findAll();

        $datas = [
            [
                'nom' => 'Assochats',
                'description' => 'Integer eget diam a diam viverra feugiat. Ut rutrum facilisis ligula, at finibus felis elementum vitae. Sed dictum eleifend vestibulum. Etiam quis mauris ac est dictum porttitor.',
                'date_debut' => DateTime::createFromFormat('Y/m/d', '2023/04/21'),
                'duree' => '04:15:00',
                'date_fin' => DateTime::createFromFormat('Y/m/d', '2023/04/21'),
                'nombre_participant' => 17,
                'salle' => $salles[4],
                'type_reservation' => $typeReservations[4],
                'gestionnaire_salle' => $gestionnaireSalles[1],
            ],
            [
                'nom' => 'Réunion Chefs de services',
                'description' => null,
                'date_debut' => DateTime::createFromFormat('Y/m/d', '2023/04/13'),
                'duree' => '02:00:00',
                'date_fin' => DateTime::createFromFormat('Y/m/d', '2023/04/13'),
                'nombre_participant' => 9,
                'salle' => $salles[1],
                'type_reservation' => $typeReservations[5],
                'gestionnaire_salle' => $gestionnaireSalles[2],
            ],
            [
                'nom' => 'Gym d\'entretien',
                'description' => 'Ut non quam in diam tempor suscipit eu eget neque. Nullam feugiat nulla vel dictum aliquet. Integer tempus auctor venenatis. Curabitur sit amet magna turpis.',
                'date_debut' => DateTime::createFromFormat('Y/m/d', '2023/04/26'),
                'duree' => '04:30:00',
                'date_fin' => DateTime::createFromFormat('Y/m/d', '2023/04/26'),
                'nombre_participant' => 22,
                'salle' => $salles[4],
                'type_reservation' => $typeReservations[4],
                'gestionnaire_salle' => $gestionnaireSalles[3],
            ],
            [
                'nom' => 'Mariage Mr et Mllle Grelot',
                'description' => null,
                'date_debut' => DateTime::createFromFormat('Y/m/d', '2023/04/15'),
                'duree' => '30:00:00',
                'date_fin' => DateTime::createFromFormat('Y/m/d', '2023/04/16'),
                'nombre_participant' => 180,
                'salle' => $salles[4],
                'type_reservation' => $typeReservations[3],
                'gestionnaire_salle' => $gestionnaireSalles[1],
            ],
        ];

        foreach ($datas as $data) {
            $reservation = new Reservation();

            $reservation->setNom($data['nom']);
            $reservation->setDescription($data['description']);
            $reservation->setDateDebut($data['date_debut']);
            $reservation->setDuree($data['duree']);
            $reservation->setDateFin($data['date_fin']);
            $reservation->setNombreParticipant($data['nombre_participant']);
            $reservation->setSalle($data['salle']);
            $reservation->setTypeReservation($data['type_reservation']);
            $reservation->setGestionnaireSalle($data['gestionnaire_salle']);

            $this->manager->persist($reservation);
        }

        // for ($i = 0; $i < 40; $i++ ) {
        //     $reservation = new Reservation();

        //     $reservation->setNom($this->faker->words(5, true));
        //     $reservation->setDescription($this->faker->optional($weight = 0.6)->text());
        //     $reservation->setDateDebut($this->faker->dateTimeBetween('-1 month', '- 1 week'));
        //     $reservation->setDuree($this->faker->;
        //     $reservation->setDateFin($this->faker->dateTimeBetween('date_debut', '+2 days'));
        //     $reservation->setNombreParticipant($this->faker->numberBetween(5, 300));
        //     $reservation->setSalle($this->faker->randomElement($salles));
        //     $reservation->setTypeReservation($this->faker->randomElement($typeReservations));
        //     $reservation->setGestionnaireSalle($this->faker->randomElement($gestionnaireSalles));

        //     $this->manager->persist($reservation);
        // }

        $this->manager->flush();
    }
}
