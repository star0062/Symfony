<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\Invitation;
use App\Form\GroupType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupController extends AbstractController
{
    

    #[Route('/groups', name: 'group_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $groups = $entityManager->getRepository(Group::class)->findAll();

        return $this->render('group/index.html.twig', [
            'groups' => $groups
        ]);
    }

    #[Route('/groups/create', name: 'group_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            
            $creator = $this->getUser(); 
            if ($creator) {
                $group->setCreator($creator);
                $entityManager->persist($group);
                $entityManager->flush();
    
                return $this->redirectToRoute('group_index');
            }
            // Si l'utilisateur n'est pas authentifié
            $this->addFlash('error', 'Vous devez être connecté pour créer un groupe.');
            return $this->redirectToRoute('app_login');
        }
    
        return $this->render('group/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/groups/invite/{id}', name: 'group_invite')]
    public function invite(Group $group, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $userId = $request->query->get('userId');
        $user = $userRepository->find($userId);

        if (!$user) {
            $this->addFlash('error', 'Utilisateur introuvable');
            return $this->redirectToRoute('group_index');
        }

        $invitation = new Invitation();
        $invitation->setGroup($group);
        $invitation->setUser($user);
        $invitation->setAccepted(false);
        $invitation->setRefused(false);

        $entityManager->persist($invitation);
        $entityManager->flush();

        $this->addFlash('success', 'Invitation envoyée !');
        return $this->redirectToRoute('group_index');
    }

    #[Route('/groups/invitation/accept/{id}', name: 'group_invitation_accept')]
    public function acceptInvitation(Invitation $invitation, EntityManagerInterface $entityManager): Response
    {
        $invitation->setAccepted(true);
        $invitation->setRefused(false);

        $group = $invitation->getGroup();
        $group->addUser($invitation->getUser()); 

        $entityManager->flush();

        return $this->redirectToRoute('group_index');
    }

    #[Route('/groups/invitation/refuse/{id}', name: 'group_invitation_refuse')]
    public function refuseInvitation(Invitation $invitation, EntityManagerInterface $entityManager): Response
    {
        $invitation->setRefused(true);
        $invitation->setAccepted(false);
        $entityManager->flush();

        return $this->redirectToRoute('group_index');
    }

    #[Route('/groups/quit/{id}', name: 'group_quit')]
    public function quitGroup(Group $group, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if ($group->getCreator() === $user) {
            $this->addFlash('error', 'Le créateur ne peut pas quitter son propre groupe, il doit le supprimer.');
            return $this->redirectToRoute('group_index');
        }

        $group->removeUser($user);
        $entityManager->flush();

        $this->addFlash('success', 'Vous avez quitté le groupe.');
        return $this->redirectToRoute('group_index');
    }

    #[Route('/groups/delete/{id}', name: 'group_delete')]
    public function deleteGroup(Group $group, EntityManagerInterface $entityManager): Response
    {
        if ($group->getCreator() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit de supprimer ce groupe.');
        }

        $entityManager->remove($group);
        $entityManager->flush();

        $this->addFlash('success', 'Groupe supprimé avec succès.');
        return $this->redirectToRoute('group_index');
    }
}
