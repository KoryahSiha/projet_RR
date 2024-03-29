<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Un compte existe déjà avec cet email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    const ROLES = ['ROLE_ADMIN', 'ROLE_USER', 'ROLE_GESTIONNAIRESAL', 'ROLE_GESTIONNAIREDOM'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(
        min: 5,
        max: 180,
    )]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 8,
        max: 255,
    )]
    #[Assert\Regex(
        pattern: '/[a-z]+/',
        match: true,
        message: 'Vous devez utiliser au moins une lettre minuscule',
        )]
    #[Assert\Regex(
        pattern: '/[A-Z]+/',
        match: true,
        message: 'Vous devez utiliser au moins une lettre majuscule',
        )]
    #[Assert\Regex(
        pattern: '/[0-9]+/',
        match: true,
        message: 'Vous devez utiliser au moins un chiffre',
        )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9]+$/',
        match: false,
        message: 'Vous devez utiliser au moins un caractère spécial',
    )]
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private ?bool $enabled = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?GestionnaireSalle $gestionnaireSalle = null;

    public function __toString(): string
    {
        return $this->email;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getGestionnaireSalle(): ?GestionnaireSalle
    {
        return $this->gestionnaireSalle;
    }

    public function setGestionnaireSalle(GestionnaireSalle $gestionnaireSalle): self
    {
        // set the owning side of the relation if necessary
        // if ($gestionnaireSalle->getUser() !== $this) {
        //     $gestionnaireSalle->setUser($this);
        // }

        $this->gestionnaireSalle = $gestionnaireSalle;

        return $this;
    }
}
