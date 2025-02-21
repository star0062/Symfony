<?php

namespace App\Repository;

use App\Entity\TeamTask;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TeamTask>
 */
class TeamTaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeamTask::class);
    }

    public function findDailyTasksForUser($user)
    {
        $today = new \DateTime();
        return $this->createQueryBuilder('tt')
            ->andWhere('tt.user = :user')
            ->andWhere('tt.dateCreate >= :today')
            ->setParameter('user', $user)
            ->setParameter('today', $today->format('Y-m-d'))
            ->getQuery()
            ->getResult();
    }
}
