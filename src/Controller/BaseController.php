<?php

namespace App\Controller;

use App\Entity\Entreprise;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class BaseController extends AbstractController
{
    protected function checkEntreprise(EntityManagerInterface $entityManager): ?RedirectResponse
    {
        $entreprise = $entityManager->getRepository(Entreprise::class)->findOneBy([]);
        if (!$entreprise) {
            $this->addFlash('warning', 'Vous devez enregistrer une entreprise avant d\'accéder à cette page.');
            return $this->redirectToRoute('entreprise_new');
        }
        return null; // Pas de redirection si une entreprise existe
    }
}