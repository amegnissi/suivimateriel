<?php

declare(strict_types=1);

namespace App\Controller\Emploie;

use App\Entity\Emploie\OperationEmploie;
use App\Enum\OperationsStatut;
use App\Form\Emploie\DemandeModificationEmploieType;
use App\Repository\Emploie\OperationEmploieRepository;
use App\Repository\Emploie\RessourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AutorisationController extends AbstractController
{
    #[Route('/demande-autorisations', name: 'app_emploie_demandes_autorisations_index', methods: ['GET'])]
    public function index(RessourceRepository $ressourceRepository,OperationEmploieRepository $operationEmploieRepository): Response
    {
        return $this->render('emploie/operations/index_autorisation.html.twig',[
            'ressources' => $ressourceRepository->findAll(),
            'operation_emploies' => $operationEmploieRepository->demandeAutorisations(),
//            'sommes'=>$operationEmploieRepository->getTotalRetenue(),
        ]);
    }

    #[Route('/demande-autorisations/{id}', name: 'app_emploie_demandes_autorisations_actions', methods: ['GET'])]
    public function indexAutorisation(Request $request, OperationEmploie $emploie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DemandeModificationEmploieType::class, $emploie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($emploie);
            $entityManager->flush();

            return $this->redirectToRoute('app_emploie_demandes_autorisations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('emploie/operations/details_demande.html.twig', [
            'emploie' => $emploie,
            'form' => $form,
        ]);
//        return $this->render('emploie/operations/index_autorisation.html.twig',[
//            'ressources' => $ressourceRepository->findAll(),
//            'operation_emploies' => $operationEmploieRepository->demandeAutorisations(),
//            'sommes'=>$operationEmploieRepository->getTotalRetenue(),
//        ]);
    }
    #[Route('/reponse-autorisations/{id}/{statut}', name: 'app_emploie_demandes_autorisations_statut', methods: ['GET'])]
    public function reponseAutorisation(Request $request, OperationEmploie $emploie,EntityManagerInterface $entityManager): Response
    {
        $statut = $request->get('statut');
        if($statut == 'YES'){

            $emploie->setAutorisation(OperationsStatut::AUTORISATION_ACCEPTEE);
            $this->addFlash('success', 'Autorisation accordee');
        } elseif ($statut == 'NO') {

            $emploie->setAutorisation(OperationsStatut::AUTORISATION_REFUSEE);
            $this->addFlash('success', 'Autorisation refusee');
        } else{
            return $this->redirectToRoute('app_emploie_demandes_autorisations_index', [], Response::HTTP_SEE_OTHER);
        }

        $entityManager->persist($emploie);
        $entityManager->flush();
        return $this->redirectToRoute('app_emploie_demandes_autorisations_index', [], Response::HTTP_SEE_OTHER);

    }
}
