<?php

declare(strict_types=1);

namespace App\Controller\Emploie;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClotureController extends AbstractController
{
    #[Route('/liste-operations-cloture', name: 'app_emploie_demandes_autorisations_index', methods: ['GET'])]
    public function index(RessourceRepository $ressourceRepository,OperationEmploieRepository $operationEmploieRepository): Response
    {}
}