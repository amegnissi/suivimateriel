<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\PasswordUpdateProfile;
use App\Entity\User;
use App\Form\PasswordUpdateUserType;
use App\Form\RegistrationForm;
use App\Form\RegistrationFormType;
use App\Form\RegistrationUpdateFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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

    #[Route(path: '/{id}/edit', name: 'app_admin_user_edit', methods: ['GET', 'POST'])]
//    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RegistrationUpdateFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy([
                'id' => $user->getId()
            ]);
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();


            return $this->redirectToRoute('app_admin_user');


        }
        return $this->render('registration/edit-utilisateur.html.twig', [
            'user' => $user,
            'pagetitle' => 'Liste',
            'title' => 'Utilisateurs',
            'registrationForm' => $form->createView(),
        ]);
    }
    #[\Symfony\Component\Routing\Annotation\Route(path: '/account/password-update/by-user', name: 'account_password_user')]
    #[IsGranted('ROLE_USER')]
    public function updatePasswordByUser(Request $request, UserPasswordHasherInterface $encoder,
                                         EntityManagerInterface $manager,Security $security)
    {
        $passwordUpdate = new PasswordUpdateProfile();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateUserType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 1. Vérifier que le oldPassword du formulaire soit le même que le password de l'user
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getPassword())) {
                // Gérer l'erreur
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel !"));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->hashPassword($user, $newPassword);

                $user->setPassword($hash);

                $manager->persist($user);
                $manager->flush();
                $security->logout(false);
                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié !"
                );

                return $this->redirectToRoute('app_login2');
            }
        }

        return $this->render('user/change_pwd_user.html.twig', [
            'form' => $form->createView()
        ]);
    }


}
