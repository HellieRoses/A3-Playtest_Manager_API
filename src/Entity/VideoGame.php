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
        new Get(
            description: "Retrieves a Video Game"
        ),
        new GetCollection(
            description: "Retrieves all videogames"
        ),
        new Post(
            description: "Creates a Video Game",
            denormalizationContext: ["groups"=>["Default","video_game:create"]],
            security: "is_granted('VIDEOGAME_CREATE', object)",
            validationContext: ["groups"=>["Default","video_game:create"]],
            processor: VideoGameProcessor::class
        ),
        new Delete(
            description: "Deletes a Video Game",
            security: "is_granted('VIDEOGAME_DELETE', object)"
        ),
        new Patch(
            description: "Updates a Video Game",
            denormalizationContext: ["groups"=>["Default","video_game:update"]],
            security: "is_granted('VIDEOGAME_MODIFY', object)",
            validationContext: ["groups"=>["Default","video_game:update"]]
        ),
        new GetCollection(
            uriTemplate: "/companies/{idCompany}/video_games",
            uriVariables: [
                "idCompany"=>new Link(
                    fromProperty: "videoGames",
                    fromClass: Company::class,
                ),
            ],
            description: "Retrieves all video games of a Company",
        )
    ],normalizationContext: ["groups"=>["Default","video_game:read"]],
)]
class VideoGame
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["video_game:create", "video_game:update", "playtest:read","video_game:read"])]
    #[Assert\NotBlank(groups: ["video_game:create"])]
    #[Assert\NotNull(groups: ["video_game:create"])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(["video_game:create", "video_game:update", "playtest:read","video_game:read"])]
    private ?string $type = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    #[Groups(["video_game:create", "video_game:update", "playtest:read","video_game:read"])]
    private ?array $support = [];

    /**
     * @var Collection<int, Playtest>
     */
    #[ORM\OneToMany(targetEntity: Playtest::class, mappedBy: 'videoGame', orphanRemoval: true)]
    private Collection $playtests;

    #[ORM\ManyToOne(inversedBy: 'videoGames')]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiProperty(writable: false)]
    #[Groups(["video_game:read"])]
    private ?Company $company = null;

    public function __construct()
    {
        $this->playtests = new ArrayCollection();
    }

    /**
     * Get id of video game
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get name of video game
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set name of video game
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get Type of video game
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Set type of Video Game
     * @param string $type
     * @return $this
     */
    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get support of Video Game
     * @return array|null
     */
    public function getSupport(): ?array
    {
        return $this->support;
    }

    /**
     * Set support of Video Game
     * @param array $support
     * @return $this
     */
    public function setSupport(array $support): static
    {
        $this->support = $support;

        return $this;
    }

    /**
     * Get playtests of video game
     * @return Collection<int, Playtest>
     */
    public function getPlaytests(): Collection
    {
        return $this->playtests;
    }

    /**
     * Add playtest to video game
     * @param Playtest $playtest
     * @return $this
     */
    public function addPlaytest(Playtest $playtest): static
    {
        if (!$this->playtests->contains($playtest)) {
            $this->playtests->add($playtest);
            $playtest->setVideoGame($this);
        }

        return $this;
    }

    /**
     * Remove playtest from video game
     * @param Playtest $playtest
     * @return $this
     */
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

    /**
     * Get company of video game
     * @return Company|null
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    /**
     * Set company of video game
     * @param Company|null $company
     * @return $this
     */
    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }
}
