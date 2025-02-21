<?php

namespace App\Service;

use App\Entity\Team;
use App\Entity\User;
use App\Entity\TeamInvitation;
use Doctrine\ORM\EntityManagerInterface;

class TeamService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createTeam(Team $team, User $creator)
    {
        $team->setUserAdd($creator);
        $team->setDateCreate(new \DateTime());
        $team->setPoint(0);

        $creator->setTeam($team);

        $this->entityManager->persist($team);
        $this->entityManager->persist($creator);
        $this->entityManager->flush();
    }

    public function addUserToTeam(User $user, Team $team)
    {
        $user->setTeam($team);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function removeUserFromTeam(User $user)
    {
        $user->setTeam(null);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function inviteUserToTeam(Team $team, User $user)
    {
        $invitation = new TeamInvitation();
        $invitation->setTeam($team);
        $invitation->setUser($user);
        $invitation->setInvitedBy($this->getUser());
        $invitation->setStatus('pending');
        $invitation->setDateCreated(new \DateTime());

        $this->entityManager->persist($invitation);
        $this->entityManager->flush();
    }


    public function deleteTeam(Team $team)
    {
        if ($team->getPoint() < 0) {
            foreach ($team->getUsers() as $user) {
                $user->setTeam(null);
                $this->entityManager->persist($user);
            }

            foreach ($team->getTeamTasks() as $task) {
                $this->entityManager->remove($task);
            }

            $this->entityManager->remove($team);
            $this->entityManager->flush();
        }
    }

    public function calculateTeamScore(Team $team)
    {
        $score = 0;

        foreach ($team->getTeamTasks() as $task) {
            if ($task->isCompleted()) { 
                switch ($task->getDifficult()) {
                    case 'difficile':
                        $score += 10;
                        break;
                    case 'moyen':
                        $score += 5;
                        break;
                    case 'facile':
                        $score += 2;
                        break;
                    case 'tres_facile':
                        $score += 1;
                        break;
                }
            } else {
                switch ($task->getDifficult()) {
                    case 'difficile':
                        $score -= 2;
                        break;
                    case 'moyen':
                        $score -= 3;
                        break;
                    case 'facile':
                        $score -= 5;
                        break;
                    case 'tres_facile':
                        $score -= 8;
                        break;
                }
            }
        }

        $team->setPoint($score);
        $this->entityManager->persist($team);
        $this->entityManager->flush();

        return $score;
    }

    public function processDailyScore(User $user)
    {
        $team = $user->getTeam();
        if (!$team) {
            return;
        }

        $scoreLost = 0;
        $responsibleUsers = [];

        $lastConnection = $user->getLastUpdate();

        $uncompletedTasks = $this->getUncompletedTasksSinceLastConnection($team, $lastConnection);

        foreach ($uncompletedTasks as $task) {
            switch ($task->getDifficult()) {
                case 'difficile':
                    $scoreLost += 2;
                    break;
                case 'moyen':
                    $scoreLost += 3;
                    break;
                case 'facile':
                    $scoreLost += 5;
                    break;
                case 'tres_facile':
                    $scoreLost += 8;
                    break;
            }

            if (!in_array($task->getUser()->getId(), $responsibleUsers)) {
                $responsibleUsers[] = $task->getUser()->getId();
            }
        }

        $team->setPoint($team->getPoint() - $scoreLost);
        $this->entityManager->persist($team);

        $user->setLastUpdate(new \DateTime());
        $this->entityManager->persist($user);

        $this->entityManager->flush();

        return [
            'scoreLost' => $scoreLost,
            'responsibleUsers' => $responsibleUsers,
        ];
    }

    private function getUncompletedTasksSinceLastConnection(Team $team, \DateTimeInterface $lastConnection)
    {
        $tasks = [];

        foreach ($team->getTeamTasks() as $task) {
            if ($task->getDateCreate() > $lastConnection) {
                $tasks[] = $task;
            }
        }

        return $tasks;
    }
}
