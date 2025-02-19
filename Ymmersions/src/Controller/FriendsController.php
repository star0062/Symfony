<?php

namespace App\Controller;

use App\Repository\FriendRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use APP\Entity\Friend;
use App\Form\FriendType;
use Symfony\Component\Validator\Constraints\IsbnValidator;

final class FriendsController extends AbstractController
{
    #[Route(path: '/amis', name: 'friends.index')]
    public function index(Request $request, FriendRepository $repository, EntityManagerInterface $em): Response
    {
        $friends = $repository->findWithDurationLowerThan(20);

        $friend = new friend();
        $friend->setUsername('phelipo')
                ->setSlug('phillipe-lipovitch')
                ->setFirstname('Phillipe')
                ->setLastname('Lipovitch');
        $em->persist($friend);
        $em->flush();
        return $this->render('friends/index.html.twig', [
            'friends' => $friends
        ]);
    }


    #[Route(path: '/amis/{slug}-{id}', name: 'friends.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(Request $request, string $slug, int $id, FriendRepository $repository): Response
    {
        $friend = $repository->find($id);
        if ($friend->getSlug() != $slug) {
            return $this->redirectToRoute('friends.show', ['slug' =>  $friend->getslug(), 'id' => $friend->getid()]);
        }
        $this-> denyAccessUnlessGranted('ROLE_USER');
        return $this -> render('friends/show.html.twig', [
            'friend' => $friend
        ]);
    }


    #[Route('/amis/{id}/edit', name: 'friends.edit')]
    public function edit(friend $friend, Request $request, EntityManagerInterface $em) {
        $form = $this->createForm(FriendType::class, $friend);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form-> isValid()) {
            $em->flush();
            $this->addFlash('success' , "La recette a bien été modifiée");
            return $this-> redirectToRoute('friends.index');
        }
        return $this->render('friends/edit.html.twig', [
            'friend' => $friend,
            'form' => $form
        ]);
    }

    #[Route('/amis/create', name: 'friends.create')]
    public function create(Request $request, EntityManagerInterface $em)
    {
        $friend = new friend();
        $form = $this->createForm(FriendType::class, $friend);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->Isvalid()) {
            $friend->setFriendAt(new \DateTimeImmutable());
            $em->persist($friend);
            $em->flush();
            $this->addFlash('success', 'La recette à bien été créée');
            return $this->redirectToRoute('friend.index');
        }
        return $this->render('friends/create.html.twig', [
            'form' => $form
        ]);
    }
}


