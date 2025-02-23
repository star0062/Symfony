<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\User; 
use App\Entity\Invitation; 

#[ORM\Entity(repositoryClass: 'App\Repository\GroupRepository')]
#[ORM\Table(name: 'groups')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'integer')]
    private int $score;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'createdGroups')]  
    private ?User $creator = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'groupsParticipated')]  
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'group', targetEntity: Invitation::class)]
    private Collection $invitations;

    /**
     * @var Collection<int, Habit>
     */
    #[ORM\OneToMany(targetEntity: Habit::class, mappedBy: 'habitGroup')]
    private Collection $habits;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->invitations = new ArrayCollection();
        $this->habits = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;
        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;
        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }
        return $this;
    }

    public function getInvitations(): Collection
    {
        return $this->invitations;
    }

    public function addInvitation(Invitation $invitation): self
    {
        if (!$this->invitations->contains($invitation)) {
            $this->invitations[] = $invitation;
        }
        return $this;
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
            $habit->setHabitGroup($this);
        }

        return $this;
    }

    public function removeHabit(Habit $habit): static
    {
        if ($this->habits->removeElement($habit)) {
            if ($habit->getHabitGroup() === $this) {
                $habit->setHabitGroup(null);
            }
        }

        return $this;
    }
}
