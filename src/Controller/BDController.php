<?php

namespace App\Controller;

use App\Entity\Entreprise;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BDController extends AbstractController
{
    #[Route('/new', name: 'app_new_entreprise')]
    public function index()
    {

    }

}
