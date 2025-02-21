<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 */
class Team
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $teamName;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateUpdate;

    /**
     * @ORM\Column(type="integer")
     */
    private $point;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="createdTeams")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userAdd;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="team", orphanRemoval=true)
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=TeamTask::class, mappedBy="team", orphanRemoval=true)
     */
    private $teamTasks;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->teamTasks = new ArrayCollection();
        $this->dateCreate = new \DateTime();
        $this->point = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeamName(): ?string
    {
        return $this->teamName;
    }

    public function setTeamName(string $teamName): self
    {
        $this->teamName = $teamName;

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

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->dateUpdate;
    }

    public function setDateUpdate(?\DateTimeInterface $dateUpdate): self
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    public function getPoint(): ?int
    {
        return $this->point;
    }

    public function setPoint(int $point): self
    {
        $this->point = $point;

        return $this;
    }

    public function getUserAdd(): ?User
    {
        return $this->userAdd;
    }

    public function setUserAdd(?User $userAdd): self
    {
        $this->userAdd = $userAdd;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setTeam($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getTeam() === $this) {
                $user->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TeamTask>
     */
    public function getTeamTasks(): Collection
    {
        return $this->teamTasks;
    }

    public function addTeamTask(TeamTask $teamTask): self
    {
        if (!$this->teamTasks->contains($teamTask)) {
            $this->teamTasks[] = $teamTask;
            $teamTask->setTeam($this);
        }

        return $this;
    }

    public function removeTeamTask(TeamTask $teamTask): self
    {
        if ($this->teamTasks->removeElement($teamTask)) {
            if ($teamTask->getTeam() === $this) {
                $teamTask->setTeam(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->teamName;
    }
}
