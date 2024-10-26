<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name:"discriminator", type:"string")]
#[ORM\DiscriminatorMap(["user" => "User", "player" => "Player", "company" => "Company"])] //TODO test remove user because discrimator map need only non-abstract class
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_LOGIN', fields: ['login'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email']), ]
#[UniqueEntity("login",message: "Login already used", entityClass: User::class)]
#[UniqueEntity("email",message: "Email already used", entityClass: User::class)]
#[ApiResource(operations: [])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(groups: ["player:create","company:create"])]
    #[Assert\NotBlank(groups: ["player:create","company:create"])]
    #[Assert\Length(min: 4, max: 20, minMessage: 'Login too short', maxMessage: "Login too long")]
    #[Groups(["player:create","company:create","player:read","company:read"])]
    private ?string $login = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(groups: ["player:create","company:create"])]
    #[Assert\NotBlank(groups: ["player:create","company:create"])]
    #[Assert\Email(message: 'Email not valid')]
    #[Groups(["player:create","company:create","player:update","company:update","player:read","company:read"])]
    private ?string $email = null;

    #[Assert\NotBlank(groups: ["player:create","company:create"])]
    #[Assert\NotNull(groups: ["player:create","company:create"])]
    #[Assert\Length(min: 8, max:30, minMessage: "Password too short", maxMessage: "Password too long")]
    #[Assert\Regex("#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\w\W]{8,30}$#", message: "Password not valid (Need at least a lowercase, an uppercase and a number)")]
    #[ApiProperty(readable: false,writable: true)]
    #[Groups(["player:create", "player:update","company:create","company:update"])]
    private ?string $plainPassword = null;

    #[UserPassword(groups: ["player:update","company:update"])]
    #[Groups(["player:update","company:update"])]
    private ?string $currentPlainPassword = null;

    #[ORM\Column]
    #[ApiProperty(readable: false,writable: false)]
    private ?string $password = null;

    #[ORM\Column]
    #[Groups(["player:read","company:read"])]
    private array $roles = [];

    public function getId(): ?int
    {
        return $this->id;
    }


    public function eraseCredentials() :void
    {
        $this->plainPassword = null;
        $this->currentPlainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): void
    {
        $this->login = $login;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function getCurrentPlainPassword(): ?string
    {
        return $this->currentPlainPassword;
    }

    public function setCurrentPlainPassword(?string $currentPlainPassword): void
    {
        $this->currentPlainPassword = $currentPlainPassword;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }
    public function addRole($role) : void
    {
        if(!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }
    }
    public function removeRole($role) : void {
        $index = array_search($role, $this->roles);
        //array_search renvoie soit l'index (la clé) soit false is rien n'est trouver
        //Préciser le !== false est bien nécessaire, car si le role se trouve à l'index 0, utiliser un simple if($index) ne vérifie pas le type! Et donc, si l'index retournait est 0, la condition ne passerait pas...!
        if ($index !== false) {
            unset($this->roles[$index]);
        }
    }

}
