<?php

namespace App\Controller;

use App\Entity\Habit;
use App\Repository\HabitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard')]
class DashboardController extends AbstractController
{
    #[Route('/habits', name: 'dashboard', methods: ['GET'])]
    public function getHabits(HabitRepository $habitRepository): JsonResponse
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->json(['error' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        $habits = $habitRepository->findBy(['user' => $user]);

        return $this->json($habits);
    }

    #[Route('/habits/{id}/complete', methods: ['POST'])]
    public function completeHabit(Habit $habit, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        // Vérifier si l'habitude appartient à l'utilisateur
        if ($habit->getUser() !== $user) {
            return $this->json(['error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        $habit->setCompleted(true);
        $em->persist($habit);
        $em->flush();

        return $this->json(['message' => 'Habit completed successfully!']);
    }
}
