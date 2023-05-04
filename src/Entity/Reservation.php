<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Soyez créatif, et pensez à un nom !")]
    #[Assert\Length(
        min: 1,
        max: 255,
    )]
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[Assert\Length(
        min: 10,
        max: 1000,
    )]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Assert\Type("DateTime")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_debut = null;
   
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $duree = null;

    #[Assert\Type("DateTime")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_fin = null;

    #[Assert\Positive]
    #[ORM\Column(nullable: true)]
    private ?int $nombre_participant = null;

    #[Assert\NotNull(message: "Veuillez renseigner une salle")]
    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Salle $salle = null;

    #[Assert\NotNull(message: "Veuillez renseigner un type de réservation")]
    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeReservation $type_reservation = null;

    #[Assert\NotNull(message: "Veuillez renseigner un gestionnaire de salle")]
    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GestionnaireSalle $gestionnaire_salle = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(?string $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getNombreParticipant(): ?int
    {
        return $this->nombre_participant;
    }

    public function setNombreParticipant(?int $nombre_participant): self
    {
        $this->nombre_participant = $nombre_participant;

        return $this;
    }

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): self
    {
        $this->salle = $salle;

        return $this;
    }

    public function getTypeReservation(): ?TypeReservation
    {
        return $this->type_reservation;
    }

    public function setTypeReservation(?TypeReservation $type_reservation): self
    {
        $this->type_reservation = $type_reservation;

        return $this;
    }

    public function getGestionnaireSalle(): ?GestionnaireSalle
    {
        return $this->gestionnaire_salle;
    }

    public function setGestionnaireSalle(?GestionnaireSalle $gestionnaire_salle): self
    {
        $this->gestionnaire_salle = $gestionnaire_salle;

        return $this;
    }
}
