<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\SecurityBundle\Security;

class SecurityController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(AuthenticationUtils $authenticationUtils, Security $security): Response
    {        
        // Si l'utilisateur est déjà connecté, on le redirige vers /dashboard
        if ($security->getUser()) {
            return $this->redirectToRoute('dashboard'); 
        }
       return $this->redirectToRoute('app_login');
    }
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Security $security): Response
    {        
        // Si l'utilisateur est déjà connecté, on le redirige vers /dashboard
        if ($security->getUser()) {
            return $this->redirectToRoute('demarrage'); 
        }
        // Récupère l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // Dernier email saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Symfony gère la déconnexion automatiquement
        throw new \LogicException('Cette méthode peut être vide - elle est interceptée par le firewall.');
    }
}