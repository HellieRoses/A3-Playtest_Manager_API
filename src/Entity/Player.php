<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\PlayerRepository;
use App\State\PlayerProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_LOGIN', fields: ['login'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity("login",message: "Login already used")]
#[UniqueEntity("email",message: "Email already used")]
#[ApiResource(operations: [
    new Get(),
    new Post(denormalizationContext: ["groups" => ["player:create"]], validationContext: ["groups" => ["Default", "player:create"]], processor: PlayerProcessor::class),
    new Patch(denormalizationContext: ["groups" => ["player:update"]], validationContext: ["groups" => ["Default", "player:update"]], processor: PlayerProcessor::class) //TODO path security with auth
])]
class Player implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(groups: ["player:create"])]
    #[Assert\NotBlank(groups: ["player:create"])]
    #[Groups(["player:create"])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(groups: ["player:create"])]
    #[Assert\NotBlank(groups: ["player:create"])]
    #[Groups(["player:create", "player:update"])]
    private ?string $firstName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(groups: ["player:create"])]
    #[Assert\NotBlank(groups: ["player:create"])]
    #[Groups(["player:create", "player:update"])]
    private ?\DateTimeInterface $birthdayDate = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(groups: ["player:create"])]
    #[Assert\NotBlank(groups: ["player:create"])]
    #[Assert\Email(message: 'Email not valid')]
    #[Groups(["player:create", "player:update"])]
    private ?string $email = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    #[Groups(["player:create", "player:update"])]
    private ?array $favoriteGames = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(groups: ["player:create"])]
    #[Assert\NotBlank(groups: ["player:create"])]
    #[Assert\Length(min: 4, max: 20, minMessage: 'Login too short', maxMessage: "Login too long")]
    #[Groups(["player:create"])]
    private ?string $login = null;

    #[Assert\NotBlank(groups: ["player:create"])]
    #[Assert\NotNull(groups: ["player:create"])]
    #[Assert\Length(min: 8, max:30, minMessage: "Password too short", maxMessage: "Password too long")]
    #[Assert\Regex("#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\w\W]{8,30}$#", message: "Password not valid (Need at least a lowercase, an uppercase and a number)")]
    #[ApiProperty(readable: false,writable: true)]
    #[Groups(["player:create", "player:update"])]
    private ?string $plainPassword = null;

    #[ORM\Column]
    #[ApiProperty(readable: false,writable: false)]
    private ?string $password = null;

    #[ORM\Column]
    private array $roles = [];



    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getBirthdayDate(): ?\DateTimeInterface
    {
        return $this->birthdayDate;
    }

    public function setBirthdayDate(\DateTimeInterface $birthdayDate): static
    {
        $this->birthdayDate = $birthdayDate;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }


    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials() : void
    {
        $this->plainPassword = null;
    }

    public function getFavoriteGames(): ?array
    {
        return $this->favoriteGames;
    }

    public function setFavoriteGames(?array $favoriteGames): static
    {
        $this->favoriteGames = $favoriteGames;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }
}
