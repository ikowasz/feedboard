<?php

namespace App\Entity;

use App\Enum\Role;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    private const DEFAULT_ROLES = [
        Role::ROLE_USER,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column(enumType: Role::class)]
    private array $roles = self::DEFAULT_ROLES;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, BandMembership>
     */
    #[ORM\OneToMany(targetEntity: BandMembership::class, mappedBy: 'member', orphanRemoval: true)]
    private Collection $bandMemberships;

    public function __construct()
    {
        $this->bandMemberships = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roleValues = array_map(fn($role) => $role->value, $this->roles);
        return array_unique($roleValues);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, BandMembership>
     */
    public function getBandMemberships(): Collection
    {
        return $this->bandMemberships;
    }

    public function addBandMembership(BandMembership $bandMembership): static
    {
        if (!$this->bandMemberships->contains($bandMembership)) {
            $this->bandMemberships->add($bandMembership);
            $bandMembership->setMember($this);
        }

        return $this;
    }

    public function removeBandMembership(BandMembership $bandMembership): static
    {
        if ($this->bandMemberships->removeElement($bandMembership)) {
            // set the owning side to null (unless already changed)
            if ($bandMembership->getMember() === $this) {
                $bandMembership->setMember(null);
            }
        }

        return $this;
    }
}
