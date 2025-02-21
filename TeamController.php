<?php

namespace App\Controller;

use App\Entity\Team;
use App\Entity\User;
use App\Entity\TeamInvitation;
use App\Form\TeamType;
use App\Service\TeamService;
use App\Repository\TeamInvitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/team")
 * @IsGranted("ROLE_USER")
 */
class TeamController extends AbstractController
{
    private $teamService;
    private $entityManager;
    private $teamInvitationRepository;

    public function __construct(TeamService $teamService, EntityManagerInterface $entityManager, TeamInvitationRepository $teamInvitationRepository)
    {
        $this->teamService = $teamService;
        $this->entityManager = $entityManager;
        $this->teamInvitationRepository = $teamInvitationRepository;
    }

    /**
     * @Route("/create", name="team_create")
     */
    public function create(Request $request): Response
    {
        if ($this->getUser()->getTeam()) {
            $this->addFlash('error', 'Vous êtes déjà dans une équipe.');
            return $this->redirectToRoute('home');
        }

        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $team->setUserAdd($this->getUser());
            $this->teamService->createTeam($team, $this->getUser());
            $this->addFlash('success', 'Équipe créée avec succès!');
            return $this->redirectToRoute('team_show', ['id' => $team->getId()]);
        }

        return $this->render('team/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="team_show")
     */
    public function show(Team $team): Response
    {
        if ($team->getId() !== $this->getUser()->getTeam()?->getId()) {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à voir cette équipe.');
            return $this->redirectToRoute('home');
        }

        return $this->render('team/show.html.twig', [
            'team' => $team,
        ]);
    }

    /**
     * @Route("/{id}/invite/{user}", name="team_invite")
     * @IsGranted("ROLE_USER")
     */
    public function invite(Team $team, User $user): Response
    {
        if ($team->getUserAdd() !== $this->getUser()) {
            $this->addFlash('error', 'Seul le chef d\'équipe peut inviter des membres.');
            return $this->redirectToRoute('team_show', ['id' => $team->getId()]);
        }

        if ($user->getTeam()) {
            $this->addFlash('error', 'Cet utilisateur est déjà dans une équipe.');
            return $this->redirectToRoute('team_show', ['id' => $team->getId()]);
        }

        $existingInvitation = $this->teamInvitationRepository->findOneBy(['team' => $team, 'user' => $user]);
        if ($existingInvitation) {
            $this->addFlash('error', 'Une invitation a déjà été envoyée à cet utilisateur.');
            return $this->redirectToRoute('team_show', ['id' => $team->getId()]);
        }

        $invitation = new TeamInvitation();
        $invitation->setTeam($team);
        $invitation->setUser($user);
        $invitation->setInvitedBy($this->getUser());
        $invitation->setStatus('pending');
        $invitation->setDateCreated(new \DateTime());

        $this->entityManager->persist($invitation);
        $this->entityManager->flush();

        $this->addFlash('success', 'Invitation envoyée à ' . $user->getPseudo() . '!');
        return $this->redirectToRoute('team_show', ['id' => $team->getId()]);
    }

    /**
     * @Route("/invite/accept/{id}", name="team_invite_accept")
     */
    public function acceptInvitation(TeamInvitation $invitation): Response
    {
        if ($invitation->getUser() !== $this->getUser()) {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à accepter cette invitation.');
            return $this->redirectToRoute('home');
        }

        if ($this->getUser()->getTeam()) {
            $this->addFlash('error', 'Vous êtes déjà dans une équipe.');
            return $this->redirectToRoute('home');
        }

        $team = $invitation->getTeam();

        $this->teamService->addUserToTeam($this->getUser(), $team);

        $invitation->setStatus('accepted');
        $this->entityManager->flush();

        $this->addFlash('success', 'Vous avez rejoint l\'équipe ' . $team->getTeamName() . '!');
        return $this->redirectToRoute('team_show', ['id' => $team->getId()]);
    }

    /**
     * @Route("/invite/reject/{id}", name="team_invite_reject")
     */
    public function rejectInvitation(TeamInvitation $invitation): Response
    {
        if ($invitation->getUser() !== $this->getUser()) {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à refuser cette invitation.');
            return $this->redirectToRoute('home');
        }

        $this->entityManager->remove($invitation);
        $this->entityManager->flush();

        $this->addFlash('success', 'Vous avez refusé l\'invitation.');
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/{id}/leave", name="team_leave")
     */
    public function leave(Team $team): Response{
    
        if ($team->getId() !== $this->getUser()->getTeam()?->getId()) {
            $this->addFlash('error', 'Vous n\'êtes pas dans cette équipe.');
            return $this->redirectToRoute('home');
        }

        $this->teamService->removeUserFromTeam($this->getUser());

        $this->addFlash('success', 'Vous avez quitté l\'équipe.');
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/{id}/delete", name="team_delete")
     */
    public function delete(Team $team): Response
    {
        if ($team->getUserAdd() !== $this->getUser()) {
            $this->addFlash('error', 'Seul le chef d\'équipe peut dissoudre l\'équipe.');
            return $this->redirectToRoute('team_show', ['id' => $team->getId()]);
        }

        if ($team->getPoint() >= 0) {
            $this->addFlash('error', 'Le score de l\'équipe doit être inférieur à zéro pour pouvoir la dissoudre.');
            return $this->redirectToRoute('team_show', ['id' => $team->getId()]);
        }

        $this->teamService->deleteTeam($team);

        $this->addFlash('success', 'L\'équipe a été dissoute.');
        return $this->redirectToRoute('home');
    }
}
