<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("reservation:read")]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Soyez créatif, et pensez à un nom !")]
    #[Assert\Length(
        min: 1,
        max: 255,
    )]
    #[ORM\Column(length: 255)]
    #[Groups("reservation:read")]
    private ?string $title = null;

    #[Assert\Length(
        min: 10,
        max: 1000,
    )]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups("reservation:read")]
    private ?string $description = null;

    #[Assert\Type("DateTime")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups("reservation:read")]
    private ?\DateTimeInterface $start = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $duration = null;

    #[Assert\Type("DateTime")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups("reservation:read")]
    private ?\DateTimeInterface $end = null;

    #[Assert\Positive]
    #[ORM\Column(nullable: true)]
    private ?int $participant_number = null;

    #[Assert\NotNull(message: "Veuillez renseigner une salle")]
    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("reservation:read")]
    private ?Salle $salle = null;

    #[Assert\NotNull(message: "Veuillez renseigner un type de réservation")]
    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("reservation:read")]
    private ?TypeReservation $type_reservation = null;

    #[Assert\NotNull(message: "Veuillez renseigner un gestionnaire de salle")]
    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GestionnaireSalle $gestionnaire_salle = null;

    #[ORM\Column(length: 7)]
    #[Groups("reservation:read")]
    private ?string $background_color = null;

    #[ORM\Column(length: 7)]
    #[Groups("reservation:read")]
    private ?string $border_color = null;

    #[ORM\Column(length: 7)]
    #[Groups("reservation:read")]
    private ?string $text_color = null;

    #[ORM\Column(nullable: true)]
    #[Groups("reservation:read")]
    private ?bool $all_day = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(nullable: true)]
    private ?int $deposit = null;

    #[ORM\Column(nullable: true)]
    private ?bool $paid = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getParticipantNumber(): ?int
    {
        return $this->participant_number;
    }

    public function setParticipantNumber(?int $participant_number): self
    {
        $this->participant_number = $participant_number;

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

    public function getBackgroundColor(): ?string
    {
        return $this->background_color;
    }

    public function setBackgroundColor(string $background_color): self
    {
        $this->background_color = $background_color;

        return $this;
    }

    public function getBorderColor(): ?string
    {
        return $this->border_color;
    }

    public function setBorderColor(string $border_color): self
    {
        $this->border_color = $border_color;

        return $this;
    }

    public function getTextColor(): ?string
    {
        return $this->text_color;
    }

    public function setTextColor(string $text_color): self
    {
        $this->text_color = $text_color;

        return $this;
    }

    public function getAllDay(): ?bool
    {
        return $this->all_day;
    }

    public function setAllDay(?bool $all_day): self
    {
        $this->all_day = $all_day;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getDeposit(): ?int
    {
        return $this->deposit;
    }

    public function setDeposit(?int $deposit): static
    {
        $this->deposit = $deposit;

        return $this;
    }

    public function isPaid(): ?bool
    {
        return $this->paid;
    }

    public function setPaid(?bool $paid): static
    {
        $this->paid = $paid;

        return $this;
    }
}
