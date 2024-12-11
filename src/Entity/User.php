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

/**
 * Class User is a Base Class used to create two type of users : Player and Company
 * It is also used to manage authentification within the same class.
 * User is abstract.
 */
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
    #[Groups(["company:read"])]
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

    /**
     * Attribute used when creating a user and when authentifying
     * @var string|null
     */
    #[Assert\NotBlank(groups: ["player:create","company:create"])]
    #[Assert\NotNull(groups: ["player:create","company:create"])]
    #[Assert\Length(min: 8, max:30, minMessage: "Password too short", maxMessage: "Password too long")]
    #[Assert\Regex("#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\w\W]{8,30}$#", message: "Password not valid (Need at least a lowercase, an uppercase and a number)")]
    #[ApiProperty(readable: false,writable: true)]
    #[Groups(["player:create", "player:update","company:create","company:update"])]
    private ?string $plainPassword = null;

    /**
     * Attribute used when user is modifying his info
     * @var string|null
     */
    #[UserPassword(groups: ["player:update","company:update"])]
    #[Groups(["player:update","company:update"])]
    private ?string $currentPlainPassword = null;

    #[ORM\Column]
    #[ApiProperty(readable: false,writable: false)]
    private ?string $password = null;

    #[ORM\Column]
    #[Groups(["player:read","company:read"])]
    #[ApiProperty(security: "is_granted('ROLE_ADMIN')", securityPostDenormalize: "is_granted('CHANGER_ROLES', object)")]
    private array $roles = [];

    /**
     * Get id of User
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set to null attribute used for creation of user (plainPassword) and modification of user (currentPlainPassword)
     * @return void
     */
    public function eraseCredentials() :void
    {
        $this->plainPassword = null;
        $this->currentPlainPassword = null;
    }

    /**
     * Return login of user
     * @return string
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }

    /**
     * Get login of user
     * @return string|null
     */
    public function getLogin(): ?string
    {
        return $this->login;
    }

    /**
     * Set login of user
     * @param string|null $login
     * @return void
     */
    public function setLogin(?string $login): void
    {
        $this->login = $login;
    }

    /**
     * Get email of user
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set email of user
     * @param string|null $email
     * @return void
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * Get plainPassword
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * Set plainPassword of user.
     * @param string|null $plainPassword
     * @return void
     */
    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * Get currentPlainPassword
     * @return string|null
     */
    public function getCurrentPlainPassword(): ?string
    {
        return $this->currentPlainPassword;
    }

    /**
     * Set currentPlainPassword
     * @param string|null $currentPlainPassword
     * @return void
     */
    public function setCurrentPlainPassword(?string $currentPlainPassword): void
    {
        $this->currentPlainPassword = $currentPlainPassword;
    }

    /**
     * Get password
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Set password
     * @param string|null $password
     * @return void
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * Get roles of users
     * @return array|string[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    /**
     * Set roles of user
     * @param array $roles
     * @return void
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Add role to user
     * @param $role
     * @return void
     */
    public function addRole($role) : void
    {
        if(!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }
    }

    /**
     * Remove role from user
     * @param $role
     * @return void
     */
    public function removeRole($role) : void {
        $index = array_search($role, $this->roles);
        //array_search renvoie soit l'index (la clé) soit false is rien n'est trouver
        //Préciser le !== false est bien nécessaire, car si le role se trouve à l'index 0, utiliser un simple if($index) ne vérifie pas le type! Et donc, si l'index retournait est 0, la condition ne passerait pas...!
        if ($index !== false) {
            unset($this->roles[$index]);
        }
    }

}
