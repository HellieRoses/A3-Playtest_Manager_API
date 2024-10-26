<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\PlayerRepository;
use App\State\UserProcessor;
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
#[ApiResource(operations: [
    new Get(),
    new Post(
        denormalizationContext: ["groups" => ["player:create"]],
        validationContext: ["groups" => ["Default", "player:create"]],
        processor: UserProcessor::class),
    new Patch(
        denormalizationContext: ["groups" => ["player:update"]],
        validationContext: ["groups" => ["Default", "player:update"]],
        processor: UserProcessor::class), //TODO path security with auth
    new Delete(), //TODO path security with auth + à l'avenir devra supprimer son inscription aux events
    new GetCollection()
    ],normalizationContext: ["groups" => ["player:read"]],
)]
class Player extends User
{
    #[ORM\Column(length: 255)]
    #[Assert\NotNull(groups: ["player:create"])]
    #[Assert\NotBlank(groups: ["player:create"])]
    #[Groups(["player:create", "player:update","player:read"])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(groups: ["player:create"])]
    #[Assert\NotBlank(groups: ["player:create"])]
    #[Groups(["player:create", "player:update","player:read"])]
    private ?string $firstName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(groups: ["player:create"])]
    #[Assert\NotBlank(groups: ["player:create"])]
    #[Groups(["player:create", "player:update","player:read"])]
    private ?\DateTimeInterface $birthdayDate = null;

    #[ORM\Column( nullable: true)]
    #[Groups(["player:create", "player:update","player:read"])]
    private ?array $favoriteGames = null;

    public function __construct()
    {
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
    public function getFavoriteGames(): ?array
    {
        return $this->favoriteGames;
    }

    public function setFavoriteGames(?array $favoriteGames): static
    {
        $this->favoriteGames = $favoriteGames;

        return $this;
    }


}
