<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Post;
use App\Repository\RegistrationRepository;
use App\State\ParticipationProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: RegistrationRepository::class)]
#[ApiResource(operations: [
    new Post(
        uriTemplate: "/playtests/participate",
        denormalizationContext: ['groups' => ['registration:create']],
        security: "is_granted('PARTICIPATION_CREATE',object)",
        validationContext: ["groups" => ["Default", "registration:create"]],
        processor: ParticipationProcessor::class,
    ),
    new Delete()
])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_REGISTRATION', fields: ['playtest', 'player'])]
class Participation
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'participants')]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiProperty(readable: true, writable: true, required: true)]
    private Playtest $playtest;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'participations')]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiProperty(readable: true, writable: false)]
    private Player $player;


    public function getPlaytest(): ?Playtest
    {
        return $this->playtest;
    }

    public function setPlaytest(?Playtest $playtest): static
    {
        $this->playtest = $playtest;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): static
    {
        $this->player = $player;

        return $this;
    }
}
