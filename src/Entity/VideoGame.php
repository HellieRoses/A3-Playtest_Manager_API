<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\VideoGameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoGameRepository::class)]
#[ApiResource]
class VideoGame
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $support = null;

    /**
     * @var Collection<int, Playtest>
     */
    #[ORM\OneToMany(targetEntity: Playtest::class, mappedBy: 'videoGame', orphanRemoval: true)]
    private Collection $playtests;

    #[ORM\ManyToOne(inversedBy: 'videoGames')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    public function __construct()
    {
        $this->playtests = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getSupport(): ?string
    {
        return $this->support;
    }

    public function setSupport(string $support): static
    {
        $this->support = $support;

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
            $playtest->setVideoGame($this);
        }

        return $this;
    }

    public function removePlaytest(Playtest $playtest): static
    {
        if ($this->playtests->removeElement($playtest)) {
            // set the owning side to null (unless already changed)
            if ($playtest->getVideoGame() === $this) {
                $playtest->setVideoGame(null);
            }
        }

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }
}
