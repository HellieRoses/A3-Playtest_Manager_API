<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Repository\PlaytestRepository;
use App\State\PlayTestProcessor;
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
        )
    ]
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
    #[Groups(["playtest:create"])]
    private ?VideoGame $videoGame = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\Type("\DateTimeInterface",groups: ["playtest:create"])]
    #[Groups(["playtest:create"])]
    private ?\DateTimeInterface $begin = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\Type("\DateTimeInterface",groups: ["playtest:create"])]
    #[Assert\GreaterThan(propertyPath: "begin")]
    #[Groups(["playtest:create"])]
    private ?\DateTimeInterface $end = null;

    #[ORM\Column(length: 255)]
    #[Groups(["playtest:create"])]
    private ?string $adress = null;

    #[ORM\ManyToOne(inversedBy: 'playtests')]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiProperty(writable: false)]
    private ?Company $company = null;

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
}
