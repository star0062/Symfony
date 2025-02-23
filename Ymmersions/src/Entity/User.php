<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: 'App\Repository\UserRepository')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // #[ORM\Id]
    // #[ORM\GeneratedValue]
    // #[ORM\Column(type: 'integer')]
    // private ?int $id = null;
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;
    

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $username;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $email;

    #[ORM\Column(type: 'string', length: 255)]
    private string $password;

    // Relation ManyToMany avec Group (les groupes auxquels l'utilisateur appartient)
    #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'users')]
    #[ORM\JoinTable(name: 'user_groups')]
    private Collection $groupsParticipated;

    // Relation OneToMany avec Group (les groupes créés par cet utilisateur)
    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Group::class)]
    private Collection $createdGroups;

    // Propriété temporaire pour le mot de passe en clair (non persistée en base)
    private ?string $plainPassword = null;

    /**
     * @var Collection<int, Habit>
     */
    #[ORM\OneToMany(targetEntity: Habit::class, mappedBy: 'creator', orphanRemoval: true)]
    private Collection $habits;

    public function __construct()
    {
        $this->groupsParticipated = new ArrayCollection();
        $this->createdGroups = new ArrayCollection();
        $this->habits = new ArrayCollection();
    }

    // Getters et setters pour la propriété plainPassword
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    // Getters et setters pour la propriété password
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    // Méthodes requises par UserInterface
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    // Getters et setters pour les autres propriétés
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getGroupsParticipated(): Collection
    {
        return $this->groupsParticipated;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groupsParticipated->contains($group)) {
            $this->groupsParticipated[] = $group;
        }
        return $this;
    }

    public function removeGroup(Group $group): self
    {
        $this->groupsParticipated->removeElement($group);
        return $this;
    }

    public function getCreatedGroups(): Collection
    {
        return $this->createdGroups;
    }

    /**
     * @return Collection<int, Habit>
     */
    public function getHabits(): Collection
    {
        return $this->habits;
    }

    public function addHabit(Habit $habit): static
    {
        if (!$this->habits->contains($habit)) {
            $this->habits->add($habit);
            $habit->setCreator($this);
        }

        return $this;
    }

    public function removeHabit(Habit $habit): static
    {
        if ($this->habits->removeElement($habit)) {
            // set the owning side to null (unless already changed)
            if ($habit->getCreator() === $this) {
                $habit->setCreator(null);
            }
        }

        return $this;
    }
}
