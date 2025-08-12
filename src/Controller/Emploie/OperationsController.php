<?php

declare(strict_types=1);

namespace App\Controller\Emploie;

use App\Repository\Emploie\OperationEmploieRepository;
use App\Repository\Emploie\RessourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OperationsController extends AbstractController
{
    #[Route('/operations/index', name: 'app_emploie_operations_index', methods: ['GET'])]
    public function index(RessourceRepository $ressourceRepository,OperationEmploieRepository $operationEmploieRepository): Response
    {
        return $this->render('emploie/operations/index.html.twig',[
            'ressources' => $ressourceRepository->findAll(),
            'operation_emploies' => $operationEmploieRepository->findAll(),
            'sommes'=>$operationEmploieRepository->getTotalRetenue(),
        ]);
    }
}
