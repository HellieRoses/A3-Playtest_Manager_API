<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\VideoGameRepository;
use App\State\VideoGameProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VideoGameRepository::class)]
#[ApiResource(
    operations:[
        new Get(),
        new GetCollection(),
        new Post(
            denormalizationContext: ["groups"=>["Default","video_game:create"]],
            security: "is_granted('VIDEOGAME_CREATE', object)",
            validationContext: ["groups"=>["Default","video_game:create"]],
            processor: VideoGameProcessor::class
        ),
        new Delete(
            security: "is_granted('VIDEOGAME_DELETE', object)"
        ),
        new Patch(
            denormalizationContext: ["groups"=>["Default","video_game:update"]],
            security: "is_granted('VIDEOGAME_PATCH', object)",
            validationContext: ["groups"=>["Default","video_game:update"]]
        )
    ]
)]
class VideoGame
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["video_game:create", "video_game:update"])]
    #[Assert\NotBlank(groups: ["video_game:create"])]
    #[Assert\NotNull(groups: ["video_game:create"])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(["video_game:create", "video_game:update"])]
    private ?string $type = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    #[Groups(["video_game:create", "video_game:update"])]
    private ?array $support = [];

    /**
     * @var Collection<int, Playtest>
     */
    #[ORM\OneToMany(targetEntity: Playtest::class, mappedBy: 'videoGame', orphanRemoval: true)]
    private Collection $playtests;

    #[ORM\ManyToOne(inversedBy: 'videoGames')]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiProperty(writable: false)]
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

    public function getSupport(): ?array
    {
        return $this->support;
    }

    public function setSupport(array $support): static
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
