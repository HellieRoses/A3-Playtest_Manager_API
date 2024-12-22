<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\CompanyRepository;
use App\State\UserProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Derived Class of User
 * A Company is an entity that can create a VideoGame and a Playtest
 */
#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ApiResource(operations: [
    new Get(
        description: "Retrieves a Company"
    ),
    new Post(
        description: "Creates a Company",
        denormalizationContext: ["groups" => ["company:create"]],
        validationContext: ["groups" => ["Default", "company:create"]],
        processor: UserProcessor::class
    ),
    new Patch(
        description: "Updates a Company. Company's password is require.",
        denormalizationContext: ["groups" => ["company:update"]],
        security: "is_granted('COMPANY_MODIFY',object)",
        validationContext: ["groups" => ["Default", "company:update"]],
        processor: UserProcessor::class
    ),
    new Delete(
        description: "Deletes a Company.",
        security: "is_granted('COMPANY_DELETE',object)"
    ),
    new GetCollection(
        description: "Retrieves all Companies",
    ),
    ],normalizationContext: ["groups" => ["company:read"]],
)]
class Company extends User
{

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(groups: ["company:create"])]
    #[Assert\NotBlank(groups: ["company:create"])]
    #[Groups(["company:create", "company:update", "playtest:read","video_game:read","company:read"])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["company:create", "company:update", "playtest:read","video_game:read","company:read"])]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(groups: ["company:create"])]
    #[Assert\NotBlank(groups: ["company:create"])]
    #[Groups(["company:create", "company:update","video_game:read","company:read","playtest:read"])]
    private ?string $adress = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(groups: ["company:create"])]
    #[Assert\NotBlank(groups: ["company:create"])]
    #[Groups(["company:create", "company:update", "playtest:read","video_game:read","company:read"])]
    private ?string $contact = null;

    /**
     * List of VideoGames
     * @var Collection<int, VideoGame>
     */
    #[ORM\OneToMany(targetEntity: VideoGame::class, mappedBy: 'company', orphanRemoval: true)]
    #[Groups(["company:read"])]
    private Collection $videoGames;

    /**
     * List of Playtests
     * @var Collection<int, Playtest>
     */
    #[ORM\OneToMany(targetEntity: Playtest::class, mappedBy: 'company', orphanRemoval: true)]
    private Collection $playtests;


    public function __construct()
    {
        $this->videoGames = new ArrayCollection();
        $this->playtests = new ArrayCollection();
    }

    /**
     * Get name of company
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set name of company
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get description of company
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set description of company
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get adress of company
     * @return string|null
     */
    public function getAdress(): ?string
    {
        return $this->adress;
    }

    /**
     * Set adress of company
     * @param string $adress
     * @return $this
     */
    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get contact of company
     * @return string|null
     */
    public function getContact(): ?string
    {
        return $this->contact;
    }

    /**
     * Set contact of company
     * @param string $contact
     * @return $this
     */
    public function setContact(string $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get video games of company
     * @return Collection<int, VideoGame>
     */
    public function getVideoGames(): Collection
    {
        return $this->videoGames;
    }

    /**
     * Add a video game to company
     * @param VideoGame $videoGame
     * @return $this
     */
    public function addVideoGame(VideoGame $videoGame): static
    {
        if (!$this->videoGames->contains($videoGame)) {
            $this->videoGames->add($videoGame);
            $videoGame->setCompany($this);
        }

        return $this;
    }

    /**
     * Remove a video game from company
     * @param VideoGame $videoGame
     * @return $this
     */
    public function removeVideoGame(VideoGame $videoGame): static
    {
        if ($this->videoGames->removeElement($videoGame)) {
            // set the owning side to null (unless already changed)
            if ($videoGame->getCompany() === $this) {
                $videoGame->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * Get playtests of company
     * @return Collection<int, Playtest>
     */
    public function getPlaytests(): Collection
    {
        return $this->playtests;
    }

    /**
     * Add a playtest to company
     * @param Playtest $playtest
     * @return $this
     */
    public function addPlaytest(Playtest $playtest): static
    {
        if (!$this->playtests->contains($playtest)) {
            $this->playtests->add($playtest);
            $playtest->setCompany($this);
        }

        return $this;
    }

    /**
     * Remove a playtest from company
     * @param Playtest $playtest
     * @return $this
     */
    public function removePlaytest(Playtest $playtest): static
    {
        if ($this->playtests->removeElement($playtest)) {
            // set the owning side to null (unless already changed)
            if ($playtest->getCompany() === $this) {
                $playtest->setCompany(null);
            }
        }

        return $this;
    }


}
