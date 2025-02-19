<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FriendsController extends AbstractController
{
    #[Route(path: '/amis', name: 'friends.index')]
    public function index(Request $request): Response
    {
        return $this->render('friends/index.html.twig');
    }


    #[Route(path: '/amis/{slug}-{id}', name: 'friends.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(Request $request, string $slug, int $id): Response
    {
        return $this -> render('friends/show.html.twig', [
            'slug' => $slug,
            'id' => $id,
            'person' => [
                'firstname' => 'Louis',
                'lastname' => 'Clou'
            ]
        ]);
    }
}
