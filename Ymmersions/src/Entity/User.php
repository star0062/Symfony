<?php

namespace App\Entity;

<<<<<<< HEAD
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity]
#[Vich\Uploadable]
=======
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
>>>>>>> Cléo
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
<<<<<<< HEAD
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $pseudo;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $mail;

    #[ORM\Column(type: 'string')]
    private $password;

    // This property is not persisted to the database
    private $plainPassword;

    #[ORM\Column(type: 'string', nullable: true)]
    private $PP;

    #[Vich\UploadableField(mapping: 'user_profile_pictures', fileNameProperty: 'PP')]
    private ?File $PPFile = null;

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
    private $team;

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
=======
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private bool $isVerified = false;

>>>>>>> Cléo
    public function getId(): ?int
    {
        return $this->id;
    }

<<<<<<< HEAD
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;
=======
    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
>>>>>>> Cléo

        return $this;
    }

<<<<<<< HEAD
    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;
=======
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
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
>>>>>>> Cléo

        return $this;
    }

<<<<<<< HEAD
    public function getPassword(): string
=======
    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
>>>>>>> Cléo
    {
        return $this->password;
    }

<<<<<<< HEAD
    public function setPassword(string $password): self
=======
    public function setPassword(string $password): static
>>>>>>> Cléo
    {
        $this->password = $password;

        return $this;
    }

<<<<<<< HEAD
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

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

    public function setPPFile(?File $PPFile = null): void
    {
        $this->PPFile = $PPFile;

        if (null !== $PPFile) {
            $this->lastUpdate = new \DateTimeImmutable();
        }
    }

    public function getPPFile(): ?File
    {
        return $this->PPFile;
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

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->mail;
    }

    public function getRoles(): array
    {
        // Return an array of roles
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
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
=======
    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
>>>>>>> Cléo

        return $this;
    }

<<<<<<< HEAD
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
=======
    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
>>>>>>> Cléo
