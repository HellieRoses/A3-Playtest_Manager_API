<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RegistrationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegistrationRepository::class)]
#[ApiResource]
class Registration
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'registrations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Playtest $playtests = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'registrations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Player $players = null;


    public function getPlaytests(): ?Playtest
    {
        return $this->playtests;
    }

    public function setPlaytests(?Playtest $playtests): static
    {
        $this->playtests = $playtests;

        return $this;
    }

    public function getPlayers(): ?Player
    {
        return $this->players;
    }

    public function setPlayers(?Player $players): static
    {
        $this->players = $players;

        return $this;
    }
}
