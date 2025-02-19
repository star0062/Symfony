<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route(path: "/",name: "home")]
   public function index (Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher):Response {
        $user = new User();
        $user-> setEmail('glouglou@gmail.com')
        ->setUsername('glouglou')
        ->setPassword($hasher->hashPassword($user, 'pluie'))
        ->setRoles([]);
        $em->persist($user);
        $em->flush();
        return $this->render('home/index.html.twig');
   }
}
