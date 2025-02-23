<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;

final class HomeController extends AbstractController
{
    #[Route(path: "/",name: "home")]
   public function index (Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher):Response {
        return $this->render('home/index.html.twig');
   }
}
