<?php

namespace App\Controller;

use App\Entity\Group;
use App\Form\GroupType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupController extends AbstractController
{
    #[Route('/group', name: 'group_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $groups = $entityManager->getRepository(Group::class)->findAll();

        if (empty($groups)) {
            throw $this->createNotFoundException('Aucun groupe trouvé');
        }

        return $this->render('group/index.html.twig', [
            'groups' => $groups
        ]);
    }

    #[Route('/group/create', name: 'group_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $group = new Group();
        
        // Utilisation de la méthode getUser() pour récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérification si l'utilisateur est connecté
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour créer un groupe.');
        }

        // Définir l'utilisateur comme le créateur du groupe
        $group->setCreator($user);

        // Créer le formulaire pour le groupe
        $form = $this->createForm(GroupType::class, $group);

        // Traiter la requête du formulaire
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Persister et enregistrer le groupe dans la base de données
            $entityManager->persist($group);
            $entityManager->flush();

            // Rediriger vers la liste des groupes
            return $this->redirectToRoute('group_index');
        }

        // Rendu du formulaire dans la vue
        return $this->render('group/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
