<?php

namespace App\Service;

use App\Entity\TeamTask;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createTask(TeamTask $task)
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    public function updateTask(TeamTask $task)
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    public function deleteTask(TeamTask $task)
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }

    public function completeTask(TeamTask $task)
    {
        $task->setCompleted(true);
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    public function uncompleteTask(TeamTask $task)
    {
        $task->setCompleted(false);
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }


    public function hasUserCreatedTaskToday(User $user): bool
    {
        $today = new \DateTime();
        $startOfDay = $today->format('Y-m-d 00:00:00');
        $endOfDay = $today->format('Y-m-d 23:59:59');

        $existingTask = $this->entityManager->getRepository(TeamTask::class)
            ->createQueryBuilder('t')
            ->where('t.user = :user')
            ->andWhere('t.dateCreate BETWEEN :start AND :end')
            ->setParameter('user', $user)
            ->setParameter('start', $startOfDay)
            ->setParameter('end', $endOfDay)
            ->getQuery()
            ->getOneOrNullResult();

        return $existingTask !== null;
    }
}
