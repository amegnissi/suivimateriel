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
    public function index(EntityManager $em)
    {
       $entreprise = new Entreprise;
       $entreprise->setNom("DGA & Fils");
       $entreprise->setEmail('dga@dag.com');

       $entreprise2 = new Entreprise;
       $entreprise2->setNom("Elise & Fils");
       $entreprise2->setEmail('dga@dag1.com');

       // prepaparation a l'insertion des donnees
       $em->persist($entreprise);
       $em->persist($entreprise2);

       // envoi des donnes dans la table

       $em->flush();

       dd($entreprise);
    }

}
