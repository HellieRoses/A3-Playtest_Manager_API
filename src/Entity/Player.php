<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
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

/**
 * Derived Class of User
 * A Player is an entity that can participate in a Playtest
 */
#[ORM\Entity(repositoryClass: PlayerRepository::class)]
#[ApiResource(operations: [
    new Get(description: "Retrieves a Player"),
    new Post(
        description: "Creates a Player",
        denormalizationContext: ["groups" => ["player:create"]],
        validationContext: ["groups" => ["Default", "player:create"]],
        processor: UserProcessor::class
    ),
    new Patch(
        description: "Updates a Player. Player's password is require.",
        denormalizationContext: ["groups" => ["player:update"]],
        security: "is_granted('PLAYER_MODIFY',object)",
        validationContext: ["groups" => ["Default", "player:update"]],
        processor: UserProcessor::class,
    ),
    new Delete(
        description: "Deletes a Player",
        security: "is_granted('PLAYER_DELETE',object)"
    ),
    new GetCollection(
        description: "Retrieves all Players"
    )
    ],
    normalizationContext: ["groups" => ["player:read"]],
)]
class Player extends User
{
    #[ORM\Column(length: 255)]
    #[Assert\NotNull(groups: ["player:create"])]
    #[Assert\NotBlank(groups: ["player:create"])]
    #[Groups(["player:create", "player:update","player:read",'participation:player:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(groups: ["player:create"])]
    #[Assert\NotBlank(groups: ["player:create"])]
    #[Groups(["player:create", "player:update","player:read",'participation:player:read'])]
    private ?string $firstName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(groups: ["player:create"])]
    #[Assert\NotBlank(groups: ["player:create"])]
    #[Groups(["player:create", "player:update","player:read",'participation:player:read'])]
    private ?\DateTimeInterface $birthdayDate = null;

    #[ORM\Column( nullable: true)]
    #[Groups(["player:create", "player:update","player:read",'participation:player:read'])]
    private ?array $favoriteGames = null;

    /**
     * List of participations
     * @var Collection<int, Participation>
     */
    #[ORM\OneToMany(targetEntity: Participation::class, mappedBy: 'player',cascade:['persist'], orphanRemoval: true)]
    private Collection $participations;

    public function __construct()
    {
        $this->participations = new ArrayCollection();
    }

    /**
     * Get name of user
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set name of user
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get first name of user
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Set first name of user
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get birthday date of user
     * @return \DateTimeInterface|null
     */
    public function getBirthdayDate(): ?\DateTimeInterface
    {
        return $this->birthdayDate;
    }

    /**
     * Set birthdayDate of user
     * @param \DateTimeInterface $birthdayDate
     * @return $this
     */
    public function setBirthdayDate(\DateTimeInterface $birthdayDate): static
    {
        $this->birthdayDate = $birthdayDate;

        return $this;
    }

    /**
     * Get favorite games of user
     * @return array|null
     */
    public function getFavoriteGames(): ?array
    {
        return $this->favoriteGames;
    }

    /**
     * Set favorite games of user
     * @param array|null $favoriteGames
     * @return $this
     */
    public function setFavoriteGames(?array $favoriteGames): static
    {
        $this->favoriteGames = $favoriteGames;

        return $this;
    }

    /**
     * Get participations of user
     * @return Collection<int, Participation>
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    /**
     * Add participations to user
     * @param Participation $participation
     * @return $this
     */
    public function addParticipation(Participation $participation): static
    {
        if (!$this->participations->contains($participation)) {
            $this->participations->add($participation);
            $participation->setPlayer($this);
        }

        return $this;
    }

    /**
     * Remove participation from user
     * @param Participation $participation
     * @return $this
     */
    public function removeParticipation(Participation $participation): static
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getPlayer() === $this) {
                $participation->setPlayer(null);
            }
        }

        return $this;
    }
}
