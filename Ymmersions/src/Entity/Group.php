<?php

// namespace App\Entity;

// use Doctrine\ORM\Mapping as ORM;

// #[ORM\Entity(repositoryClass: 'App\Repository\GroupRepository')]
// #[ORM\Table(name: 'groups')]  // Change from 'group' to 'groups'

// class Group
// {
//     #[ORM\Id]
//     #[ORM\GeneratedValue]
//     #[ORM\Column(type: 'integer')]
//     private ?int $id = null;

//     #[ORM\Column(type: 'string', length: 255)]
//     private string $name;

//     #[ORM\Column(type: 'integer')]
//     private int $score = 0;

//     #[ORM\ManyToOne(targetEntity: 'App\Entity\User')]
//     #[ORM\JoinColumn(name: 'creator_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
//     private ?User $creator;

//     // Getters and setters...

//     public function getId(): ?int
//     {
//         return $this->id;
//     }

//     public function getName(): string
//     {
//         return $this->name;
//     }

//     public function setName(string $name): self
//     {
//         $this->name = $name;
//         return $this;
//     }

//     public function getScore(): int
//     {
//         return $this->score;
//     }

//     public function setScore(int $score): self
//     {
//         $this->score = $score;
//         return $this;
//     }

//     public function getCreator(): ?User
//     {
//         return $this->creator;
//     }

//     public function setCreator(?User $creator): self
//     {
//         $this->creator = $creator;
//         return $this;
//     }
// }






// namespace App\Entity;

// use Doctrine\ORM\Mapping as ORM;
// use Doctrine\Common\Collections\ArrayCollection;
// use Doctrine\Common\Collections\Collection;

// use App\Entity\User;
// use App\Entity\Invitation;

// /**
//  * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
//  * @ORM\Table(name="groups")  // Changer le nom de la table à 'groups'
//  */
// #[ORM\Entity(repositoryClass: 'App\Repository\GroupRepository')]
// #[ORM\Table(name: 'groups')]  // Changer le nom de la table à 'groups'
// class Group
// {
//     #[ORM\Id]
//     #[ORM\GeneratedValue]
//     #[ORM\Column(type: 'integer')]
//     private int $id;

//     #[ORM\Column(type: 'string', length: 255)]
//     private string $name;

//     #[ORM\Column(type: 'integer')]
//     private int $score;

//     // Relation ManyToOne avec User (le créateur du groupe)
//     #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'groups')]
//     private ?User $creator = null;

//     // Relation ManyToMany avec User (les utilisateurs qui font partie du groupe)
//     #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'groups')]
//     private Collection $users;

//     // Relation OneToMany avec Invitation (les invitations pour rejoindre le groupe)
//     #[ORM\OneToMany(mappedBy: 'group', targetEntity: Invitation::class)]
//     private Collection $invitations;

//     public function __construct()
//     {
//         $this->users = new ArrayCollection();
//         $this->invitations = new ArrayCollection();
//     }

//     // Getters et Setters

//     public function getId(): int
//     {
//         return $this->id;
//     }

//     public function getName(): string
//     {
//         return $this->name;
//     }

//     public function setName(string $name): self
//     {
//         $this->name = $name;
//         return $this;
//     }

//     public function getScore(): int
//     {
//         return $this->score;
//     }

//     public function setScore(int $score): self
//     {
//         $this->score = $score;
//         return $this;
//     }

//     public function getCreator(): ?User
//     {
//         return $this->creator;
//     }

//     public function setCreator(?User $creator): self
//     {
//         $this->creator = $creator;
//         return $this;
//     }

//     public function getUsers(): Collection
//     {
//         return $this->users;
//     }

//     public function addUser(User $user): self
//     {
//         if (!$this->users->contains($user)) {
//             $this->users[] = $user;
//         }
//         return $this;
//     }

//     public function getInvitations(): Collection
//     {
//         return $this->invitations;
//     }

//     public function addInvitation(Invitation $invitation): self
//     {
//         if (!$this->invitations->contains($invitation)) {
//             $this->invitations[] = $invitation;
//         }
//         return $this;
//     }


//     public function removeUser(User $user): self
//     {
//         if ($this->users->contains($user)) {
//             $this->users->removeElement($user);
//         }
//         return $this;
//     }
    
// }


// src/Entity/User.php



// namespace App\Entity;

// use Doctrine\ORM\Mapping as ORM;
// use Doctrine\Common\Collections\ArrayCollection;
// use Doctrine\Common\Collections\Collection;
// use App\Entity\Group;
// use App\Entity\Invitation;

// #[ORM\Entity(repositoryClass: 'App\Repository\UserRepository')]
// class User
// {
//     // ... autres propriétés et méthodes

//     // Relation OneToMany avec Group (les groupes créés par l'utilisateur)
//     #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Group::class)]
//     private Collection $groups;

//     // Relation ManyToMany avec Group (les groupes auxquels l'utilisateur appartient)
//     #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'users')]
//     private Collection $groupsParticipated;

//     public function __construct()
//     {
//         $this->groups = new ArrayCollection();
//         $this->groupsParticipated = new ArrayCollection();
//     }

//     public function getGroups(): Collection
//     {
//         return $this->groups;
//     }

//     public function addGroup(Group $group): self
//     {
//         if (!$this->groups->contains($group)) {
//             $this->groups[] = $group;
//         }
//         return $this;
//     }

//     public function getGroupsParticipated(): Collection
//     {
//         return $this->groupsParticipated;
//     }

//     public function addGroupParticipated(Group $group): self
//     {
//         if (!$this->groupsParticipated->contains($group)) {
//             $this->groupsParticipated[] = $group;
//         }
//         return $this;
//     }
// }




namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\User; // Assurez-vous d'importer l'entité User
use App\Entity\Invitation; // Assurez-vous d'importer l'entité Invitation

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

    // Relation ManyToOne avec User (le créateur du groupe)
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'createdGroups')]  // Correction de 'groups' vers 'createdGroups'
    private ?User $creator = null;

    // Relation ManyToMany avec User (les utilisateurs qui font partie du groupe)
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'groupsParticipated')]  // Correction pour 'groupsParticipated'
    private Collection $users;

    // Relation OneToMany avec Invitation (les invitations pour rejoindre le groupe)
    #[ORM\OneToMany(mappedBy: 'group', targetEntity: Invitation::class)]
    private Collection $invitations;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->invitations = new ArrayCollection();
    }

    // Getters et setters

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
}
