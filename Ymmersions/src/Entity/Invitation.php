<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvitationRepository")
 */
#[ORM\Entity(repositoryClass: 'App\Repository\InvitationRepository')]
class Invitation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Group::class, inversedBy: 'invitations')]
    #[ORM\JoinColumn(nullable: false)]
    private Group $group;

    // #[ORM\ManyToOne(targetEntity: User::class)]
    // #[ORM\JoinColumn(nullable: false)]
    // private User $user;
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $accepted = false;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $refused = false;

    // Getters and Setters for all properties
    public function getId(): int
    {
        return $this->id;
    }

    public function getGroup(): Group
    {
        return $this->group;
    }

    public function setGroup(Group $group): self
    {
        $this->group = $group;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function isAccepted(): bool
    {
        return $this->accepted;
    }

    public function setAccepted(bool $accepted): self
    {
        $this->accepted = $accepted;
        return $this;
    }

    public function isRefused(): bool
    {
        return $this->refused;
    }

    public function setRefused(bool $refused): self
    {
        $this->refused = $refused;
        return $this;
    }
}
