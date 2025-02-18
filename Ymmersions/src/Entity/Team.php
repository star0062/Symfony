<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

#[ORM\Entity]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $userAdd;

    #[ORM\Column(type: 'string', length: 255)]
    private $teamName;

    #[ORM\Column(type: 'datetime')]
    private $dateCreate;

    #[ORM\Column(type: 'datetime')]
    private $dateUpdate;

    #[ORM\Column(type: 'integer')]
    private $point = 0;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'idteam')]
    private Collection $members;

    /**
     * @var Collection<int, TeamTask>
     */
    #[ORM\OneToMany(targetEntity: TeamTask::class, mappedBy: 'team', orphanRemoval: true)]
    private Collection $tasks;

    /**
     * @var Collection<int, Score>
     */
    #[ORM\OneToMany(targetEntity: Score::class, mappedBy: 'team', orphanRemoval: true)]
    private Collection $scores;

    public function __construct()
    {
        $this->dateCreate = new \DateTime();
        $this->dateUpdate = new \DateTime();
        $this->members = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->scores = new ArrayCollection();
    }

    // Getters and setters for each property...

    public function getId(): ?int
    {
        return $this->id;
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

    public function setDateUpdate(\DateTimeInterface $dateUpdate): self
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

    /**
     * @return Collection<int, User>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(User $member): static
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
            $member->setIdteam($this);
        }

        return $this;
    }

    public function removeMember(User $member): static
    {
        if ($this->members->removeElement($member)) {
            // set the owning side to null (unless already changed)
            if ($member->getIdteam() === $this) {
                $member->setIdteam(null);
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
            $task->setTeam($this);
        }

        return $this;
    }

    public function removeTask(TeamTask $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getTeam() === $this) {
                $task->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Score>
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function addScore(Score $score): static
    {
        if (!$this->scores->contains($score)) {
            $this->scores->add($score);
            $score->setTeam($this);
        }

        return $this;
    }

    public function removeScore(Score $score): static
    {
        if ($this->scores->removeElement($score)) {
            // set the owning side to null (unless already changed)
            if ($score->getTeam() === $this) {
                $score->setTeam(null);
            }
        }

        return $this;
    }
}