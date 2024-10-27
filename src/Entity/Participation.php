<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Repository\ParticipationRepository;
use App\State\ParticipationProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class that manages participation of a PLayer to an event (Playtest)
 */
#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
#[ApiResource(operations: [
        new Post(
            uriTemplate: "/playtests/participate",
            description: "Creates a participation",
            denormalizationContext: ['groups' => ['participation:create']],
            security: "is_granted('PARTICIPATION_CREATE',object)",
            validationContext: ["groups" => ["Default", "participation:create"]],
            processor: ParticipationProcessor::class,
        ),
        new Delete(
            uriTemplate: "/playtests/participate/{id}",
            security: "is_granted('PARTICIPATION_DELETE',object)",
            description: "Deletes a participation",
        ),
        new GetCollection(
            uriTemplate: '/playtests/{idPlaytest}/players',
            uriVariables: [
                "idPlaytest" => new Link(
                    fromProperty: 'participants',
                    fromClass: Playtest::class
                )
            ],
            description: "Get all Players for a Playtest",
            normalizationContext: ['groups' => ['participation:player:read']]
        ),
        new GetCollection(
            uriTemplate: '/players/{idPlayer}/playtests',
            uriVariables: [
                "idPlayer" => new Link(
                    fromProperty: 'participations',
                    fromClass: Player::class
                )
            ],
            description: "Get all Playtests that Player participates in",
            normalizationContext: ['groups' => ["participation:playtest:read"]]
        )
    ],
    normalizationContext: ["groups" => ["participation:read"]],
    denormalizationContext: ["groups" => ["participation:create"]],
    validationContext: ["groups" => ["Default", "participation:create"]],
)]

#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_PARTICIPATION', fields: ['playtest', 'player'])]
#[UniqueEntity(fields: ['playtest', 'player'], message: "A plyer can not participate several times at the same playtest")]
class Participation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['participation:read', 'player:read', 'playtest:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'participants')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    #[Assert\NotBlank(groups:["participation:create"])]
    #[Assert\NotNull(groups:["participation:create"])]
    #[ApiProperty(readable: true, writable: true, required: true)]
    #[Groups(groups: ["participation:read", "participation:create","player:read","participation:playtest:read"])]
    private Playtest $playtest;

    #[ORM\ManyToOne(inversedBy: 'participations')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    #[ApiProperty(readable: true, writable: false)]
    #[Groups(groups: ["participation:read", "participation:create","playtest:read",'participation:player:read'])]
    private Player $player;


    /**
     * Get id of participation
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get playtest of participation
     * @return Playtest|null
     */
    public function getPlaytest(): ?Playtest
    {
        return $this->playtest;
    }

    /**
     * Set playtest of participation
     * @param Playtest|null $playtest
     * @return $this
     */
    public function setPlaytest(?Playtest $playtest): static
    {
        $this->playtest = $playtest;

        return $this;
    }

    /**
     * Get player of participation
     * @return Player|null
     */
    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    /**
     * Set player of participation
     * @param Player|null $player
     * @return $this
     */
    public function setPlayer(?Player $player): static
    {
        $this->player = $player;

        return $this;
    }
}
