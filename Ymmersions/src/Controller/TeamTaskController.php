<?php

namespace App\Controller;

use App\Entity\TeamTask;
use App\Form\TeamTaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/team/task')]
final class TeamTaskController extends AbstractController
{
    #[Route(name: 'app_team_task_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $teamTasks = $entityManager
            ->getRepository(TeamTask::class)
            ->findAll();

        return $this->render('team_task/index.html.twig', [
            'team_tasks' => $teamTasks,
        ]);
    }

    #[Route('/new', name: 'app_team_task_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $teamTask = new TeamTask();
        $form = $this->createForm(TeamTaskType::class, $teamTask);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($teamTask);
            $entityManager->flush();

            return $this->redirectToRoute('app_team_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('team_task/new.html.twig', [
            'team_task' => $teamTask,
            'form' => $form,
        ]);
    }

    #[Route('/{task_id}', name: 'app_team_task_show', methods: ['GET'])]
    public function show(TeamTask $teamTask): Response
    {
        return $this->render('team_task/show.html.twig', [
            'team_task' => $teamTask,
        ]);
    }

    #[Route('/{task_id}/edit', name: 'app_team_task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TeamTask $teamTask, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TeamTaskType::class, $teamTask);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_team_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('team_task/edit.html.twig', [
            'team_task' => $teamTask,
            'form' => $form,
        ]);
    }

    #[Route('/{task_id}', name: 'app_team_task_delete', methods: ['POST'])]
    public function delete(Request $request, TeamTask $teamTask, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$teamTask->getTask_id(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($teamTask);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_team_task_index', [], Response::HTTP_SEE_OTHER);
    }
}
