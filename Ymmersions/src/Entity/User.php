<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $pseudo;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $mail;

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', nullable: true)]
    private $PP;

    #[ORM\Column(type: 'integer')]
    private $level = 1;

    #[ORM\Column(type: 'integer')]
    private $HP = 100;

    #[ORM\Column(type: 'datetime')]
    private $dateCreate;

    #[ORM\Column(type: 'datetime')]
    private $lastUpdate;

    #[ORM\ManyToOne(targetEntity: Team::class)]
    #[ORM\JoinColumn(nullable: true)]
    private $idteam;

    /**
     * @var Collection<int, Team>
     */
    #[ORM\OneToMany(targetEntity: Team::class, mappedBy: 'userAdd', orphanRemoval: true)]
    private Collection $teamName;

    /**
     * @var Collection<int, TeamTask>
     */
    #[ORM\OneToMany(targetEntity: TeamTask::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $tasks;

    public function __construct()
    {
        $this->dateCreate = new \DateTime();
        $this->lastUpdate = new \DateTime();
        $this->teamName = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    // Getters and setters for each property...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPP(): ?string
    {
        return $this->PP;
    }

    public function setPP(?string $PP): self
    {
        $this->PP = $PP;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getHP(): ?int
    {
        return $this->HP;
    }

    public function setHP(int $HP): self
    {
        $this->HP = $HP;

        return $this;
    }

    public function getDateCreate(): ?\DateTimeInterface
    {
        return $this->dateCreate;
    }

    public function setDateCreate(\DateTimeInterface $dateCreate): self
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    public function getLastUpdate(): ?\DateTimeInterface
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(\DateTimeInterface $lastUpdate): self
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    public function getIdteam(): ?Team
    {
        return $this->idteam;
    }

    public function setIdteam(?Team $idteam): self
    {
        $this->idteam = $idteam;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->mail;
    }

    public function getRoles(): array
    {
        // Return an empty array as roles are not used in this project
        return [];
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Team>
     */
    public function getTeamName(): Collection
    {
        return $this->teamName;
    }

    public function addTeamName(Team $teamName): static
    {
        if (!$this->teamName->contains($teamName)) {
            $this->teamName->add($teamName);
            $teamName->setUserAdd($this);
        }

        return $this;
    }

    public function removeTeamName(Team $teamName): static
    {
        if ($this->teamName->removeElement($teamName)) {
            // set the owning side to null (unless already changed)
            if ($teamName->getUserAdd() === $this) {
                $teamName->setUserAdd(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TeamTask>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(TeamTask $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setUser($this);
        }

        return $this;
    }

    public function removeTask(TeamTask $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getUser() === $this) {
                $task->setUser(null);
            }
        }

        return $this;
    }
}