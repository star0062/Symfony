<?php

namespace App\Controller;

use App\Entity\TeamTask;
use App\Form\TaskType;
use App\Service\TaskService;
use App\Repository\TeamTaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/task")
 * @IsGranted("ROLE_USER")
 */
class TaskController extends AbstractController
{
    private $taskService;
    private $teamTaskRepository;

    public function __construct(TaskService $taskService, TeamTaskRepository $teamTaskRepository)
    {
        $this->taskService = $taskService;
        $this->teamTaskRepository = $teamTaskRepository;
    }

    /**
     * @Route("/create", name="task_create")
     */
    public function create(Request $request): Response
    {
        $user = $this->getUser();
        $team = $user->getTeam();

        if (!$team) {
            $this->addFlash('error', 'Vous devez faire partie d\'une équipe pour créer une tâche.');
            return $this->redirectToRoute('home');
        }

        if ($this->taskService->hasUserCreatedTaskToday($user)) {
            $this->addFlash('error', 'Vous ne pouvez créer qu\'une seule tâche par jour.');
            return $this->redirectToRoute('home');
        }

        $task = new TeamTask();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setTeam($team);
            $task->setUser($user);
            $task->setPoint($task->getDifficult());
            $this->taskService->createTask($task);

            $this->addFlash('success', 'Tâche créée avec succès !');
            return $this->redirectToRoute('home');
        }

        return $this->render('task/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/complete", name="task_complete")
     */
    public function complete(TeamTask $task): Response
    {
        $user = $this->getUser();
        $team = $user->getTeam();

        if ($task->getTeam() !== $team || ($task->getTarget() === 'individual' && $task->getUser() !== $user)) {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à compléter cette tâche.');
            return $this->redirectToRoute('home');
        }

        $this->taskService->completeTask($task);
        $this->addFlash('success', 'Tâche complétée !');

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/{id}/uncomplete", name="task_uncomplete")
     */
    public function uncomplete(TeamTask $task): Response
    {
        $user = $this->getUser();
        $team = $user->getTeam();

        if ($task->getTeam() !== $team || ($task->getTarget() === 'individual' && $task->getUser() !== $user)) {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à décompléter cette tâche.');
            return $this->redirectToRoute('home');
        }

        $this->taskService->uncompleteTask($task);
        $this->addFlash('success', 'Tâche décomplétée !');

        return $this->redirectToRoute('home');
    }


    /**
     * @Route("/{id}/edit", name="task_edit")
     */
    public function edit(Request $request, TeamTask $task): Response
    {
        $user = $this->getUser();
        $team = $user->getTeam();

        if ($task->getTeam() !== $team || $task->getUser() !== $user) {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à modifier cette tâche.');
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskService->updateTask($task);
            $this->addFlash('success', 'Tâche modifiée avec succès !');
            return $this->redirectToRoute('home');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="task_delete")
     */
    public function delete(TeamTask $task): Response
    {
        $user = $this->getUser();
        $team = $user->getTeam();

        if ($task->getTeam() !== $team || $task->getUser() !== $user) {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à supprimer cette tâche.');
            return $this->redirectToRoute('home');
        }

        $this->taskService->deleteTask($task);
        $this->addFlash('success', 'Tâche supprimée avec succès !');

        return $this->redirectToRoute('home');
    }
}
