<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationForm;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/utilisateur')]
class UtilisateurController extends AbstractController
{

    #[Route(path: '/', name: 'app_admin_user', methods: ['POST', 'GET'])]

    public function index(UserRepository $userRepository): Response
    {

        return $this->render('user/index-user1.html.twig', [
            'users' => $userRepository->findAll(),
            'pagetitle' => 'Liste',
            'title' => 'Utilisateurs',

        ]);
    }
    #[Route(path: '/nouveau', name: 'app_admin_user_new', methods: ['GET', 'POST'])]

    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager)
    {

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            $mane = $form->get('employe')->getData();
            $user->setName($mane->getNom());
            $user->setFirstName($mane->getPrenom());
            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            $this->addFlash(
                'success',
                "Votre enregistrement a été effectué avec succès!"
            );


            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);

    }

}
