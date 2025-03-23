<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/index', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('baseC.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }


    #[Route('/test', name: 'test')]
    public function test(): Response
    {
        return $this->render('index/test.html.twig', [
            'controller_name' => 'Narcisse',
            'surnom'=> 'DE BORBI' .  ' STUDENT OF TEACHER DGA'
        ]);
    }

}
