<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
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
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_LOGIN', fields: ['login'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity("login",message: "Login already used")]
#[UniqueEntity("email",message: "Email already used")]
#[ApiResource(operations: [
    new Get(),
    new Post(denormalizationContext: ["groups" => ["company:create"]], validationContext: ["groups" => ["Default", "company:create"]], processor: PlayerProcessor::class),
    new Patch(denormalizationContext: ["groups" => ["company:update"]], validationContext: ["groups" => ["Default", "company:update"]], processor: PlayerProcessor::class) //TODO  path security with auth
])]
class Company implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(groups: ["company:create"])]
    #[Assert\NotBlank(groups: ["company:create"])]
    #[Groups(["company:create","company:update"])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["company:create"])]
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
    #[ORM\OneToMany(targetEntity: VideoGame::class, mappedBy: 'company')]
    private Collection $videoGames;

    /**
     * @var Collection<int, Playtest>
     */
    #[ORM\OneToMany(targetEntity: Playtest::class, mappedBy: 'company', orphanRemoval: true)]
    private Collection $playtests;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(groups: ["company:create"])]
    #[Assert\NotBlank(groups: ["company:create"])]
    #[Assert\Email(message: 'Email not valid')]
    #[Groups(["company:create","company:update"])]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(groups: ["company:create"])]
    #[Assert\NotBlank(groups: ["company:create"])]
    #[Assert\Length(min: 4, max: 20, minMessage: 'Login too short', maxMessage: "Login too long")]
    #[Groups(["company:create"])]
    private ?string $login = null;

    #[ORM\Column]
    #[ApiProperty(readable: false,writable: false)]
    private ?string $password = null;

    #[Assert\NotBlank(groups: ["company:create"])]
    #[Assert\NotNull(groups: ["company:create"])]
    #[Assert\Length(min: 8, max:30, minMessage: "Password too short", maxMessage: "Password too long")]
    #[Assert\Regex("#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\w\W]{8,30}$#", message: "Password not valid (Need at least a lowercase, an uppercase and a number)")]
    #[ApiProperty(readable: false,writable: true)]
    #[Groups(["company:create","company:update"])]
    private ?string $plainPassword = null;

    public function __construct()
    {
        $this->videoGames = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }
}
