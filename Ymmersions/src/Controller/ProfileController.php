<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\ProfileType;
use App\Entity\User;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/profile')]
#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'app_profile')]
    public function editProfile(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger)
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('plainPassword')->getData()) {
                $hashedPassword = $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData());
                $user->setPassword($hashedPassword);
            }
             // Gestion de l'upload de la photo de profil
            $profilePictureFile = $form->get('profilePicture')->getData();
            if ($profilePictureFile) {
                // Générez un nom de fichier unique et sécurisé
                $originalFilename = pathinfo($profilePictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$profilePictureFile->guessExtension();

                // Déplace le fichier dans le répertoire cible (défini dans .env et services.yaml)
                try {
                    $profilePictureFile->move(
                        $this->getParameter('profile_pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'exception si le déplacement échoue
                    $this->addFlash('danger', 'Une erreur est survenue lors du téléchargement de votre photo.');
                    return $this->redirectToRoute('app_profile_edit');
                }

                // Met à jour l'entité User avec le nouveau nom de fichier
                $user->setProfilePicture($newFilename);
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Profil mis à jour !');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
