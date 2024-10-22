<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\CompanyRepository;
use App\State\PlayerProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ApiResource(operations: [
    new Get(),
    new Post(denormalizationContext: ["groups" => ["company:create"]], validationContext: ["groups" => ["Default", "company:create"]], processor: PlayerProcessor::class),
    new Patch(denormalizationContext: ["groups" => ["company:update"]], validationContext: ["groups" => ["Default", "company:update"]], processor: PlayerProcessor::class), //TODO  path security with auth
    new Delete() //TODO path security with auth
])]
class Company extends User
{

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(groups: ["company:create"])]
    #[Assert\NotBlank(groups: ["company:create"])]
    #[Groups(["company:create","company:update"])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["company:create", "company:update"])]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(groups: ["company:create"])]
    #[Assert\NotBlank(groups: ["company:create"])]
    #[Groups(["company:create","company:update"])]
    private ?string $adress = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(groups: ["company:create"])]
    #[Assert\NotBlank(groups: ["company:create"])]
    #[Groups(["company:create","company:update"])]
    private ?string $contact = null;

    /**
     * @var Collection<int, VideoGame>
     */
    #[ORM\OneToMany(targetEntity: VideoGame::class, mappedBy: 'company', orphanRemoval: true)]
    private Collection $videoGames;

    /**
     * @var Collection<int, Playtest>
     */
    #[ORM\OneToMany(targetEntity: Playtest::class, mappedBy: 'company', orphanRemoval: true)]
    private Collection $playtests;


    public function __construct()
    {
        $this->videoGames = new ArrayCollection();
        $this->playtests = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @return Collection<int, VideoGame>
     */
    public function getVideoGames(): Collection
    {
        return $this->videoGames;
    }

    public function addVideoGame(VideoGame $videoGame): static
    {
        if (!$this->videoGames->contains($videoGame)) {
            $this->videoGames->add($videoGame);
            $videoGame->setCompany($this);
        }

        return $this;
    }

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
     * @return Collection<int, Playtest>
     */
    public function getPlaytests(): Collection
    {
        return $this->playtests;
    }

    public function addPlaytest(Playtest $playtest): static
    {
        if (!$this->playtests->contains($playtest)) {
            $this->playtests->add($playtest);
            $playtest->setCompany($this);
        }

        return $this;
    }

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
