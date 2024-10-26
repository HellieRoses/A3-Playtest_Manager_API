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
use App\Repository\PlaytestRepository;
use App\State\PlayTestProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PlaytestRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            denormalizationContext: ["groups"=>["Default","playtest:create"]],
            security: "is_granted('PLAYTEST_CREATE',object)",
            validationContext: ["groups"=>["Default","playtest:create"]],
            processor: PlayTestProcessor::class
        ),
        new Patch(
            denormalizationContext: ["groups"=>["Default","playtest:update"]],
            security: "is_granted('PLAYTEST_MODIFY',object)",
            validationContext: ["groups"=>["Default","playtest:update"]],
        ),
        new Get(),
        new GetCollection(),
        new Delete(
            security: "is_granted('PLAYTEST_MODIFY',object))",
        ),
        new GetCollection(
            uriTemplate: '/companies/{idCompany}/playtests',
            uriVariables: [
                "idCompany" => new Link(
                    fromProperty: "playtests",
                    fromClass: Company::class
                )
            ]
        )
    ],normalizationContext: ["groups"=>["Default","playtest:read"]],
)]
class Playtest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'playtests')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(groups: ["playtest:create"])]
    #[Assert\NotNull(groups: ["playtest:create"])]
    #[Groups(["playtest:create", "playtest:read","participation:playtest:read"])]
    private ?VideoGame $videoGame = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\Type("\DateTimeInterface",groups: ["playtest:create","playtest:update"])]
    #[Groups(["playtest:create","playtest:update", "playtest:read","participation:playtest:read"])]
    private ?\DateTimeInterface $begin = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\Type("\DateTimeInterface",groups: ["playtest:create","playtest:update"])]
    #[Assert\GreaterThan(propertyPath: "begin")]
    #[Groups(["playtest:create","playtest:update", "playtest:read","participation:playtest:read"])]
    private ?\DateTimeInterface $end = null;

    #[ORM\Column(length: 255)]
    #[Groups(["playtest:create","playtest:update", "playtest:read","participation:playtest:read"])]
    private ?string $adress = null;

    #[ORM\ManyToOne(inversedBy: 'playtests')]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiProperty(writable: false)]
    #[Groups(["playtest:read"])]
    private ?Company $company = null;

    #[ORM\Column]
    #[Assert\NotBlank(groups: ["playtest:create"])]
    #[Assert\NotNull(groups: ["playtest:create"])]
    #[Groups(["playtest:create", "playtest:read"])]
    private ?bool $visibility = null;

    #[ORM\Column]
    #[Groups(["playtest:create","playtest:update", "playtest:read"])]
    private ?int $nbMaxPlayer = null;

    /**
     * @var Collection<int, Participation>
     */
    #[ORM\OneToMany(targetEntity: Participation::class, mappedBy: 'playtest', cascade: ['persist'],orphanRemoval: true)]
    #[Groups(['playtest:read'])]
    private Collection $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVideoGame(): ?VideoGame
    {
        return $this->videoGame;
    }

    public function setVideoGame(?VideoGame $videoGame): static
    {
        $this->videoGame = $videoGame;

        return $this;
    }

    public function getBegin(): ?\DateTimeInterface
    {
        return $this->begin;
    }

    public function setBegin(\DateTimeInterface $begin): static
    {
        $this->begin = $begin;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): static
    {
        $this->end = $end;

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

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function isVisibility(): ?bool
    {
        return $this->visibility;
    }

    public function setVisibility(bool $visibility): static
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getNbMaxPlayer(): ?int
    {
        return $this->nbMaxPlayer;
    }

    public function setNbMaxPlayer(int $nbMaxPlayer): static
    {
        $this->nbMaxPlayer = $nbMaxPlayer;

        return $this;
    }

    /**
     * @return Collection<int, Participation>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipation(Participation $participation): static
    {
        if (!$this->participants->contains($participation)) {
            $this->participants->add($participation);
            $participation->setPlaytest($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): static
    {
        if ($this->participants->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getPlaytest() === $this) {
                $participation->setPlaytest(null);
            }
        }

        return $this;
    }
}
