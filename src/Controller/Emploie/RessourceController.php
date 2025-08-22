<?php

namespace App\Controller\Emploie;

use App\Entity\Emploie\Ressource;
use App\Form\Emploie\RessourceType;
use App\Repository\Emploie\OperationEmploieRepository;
use App\Repository\Emploie\RessourceRepository;
use App\Service\UniqueIdentifierGenerator;
use App\Traits\InsertionReferenceTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/emploie/operations/ressources')]
final class RessourceController extends AbstractController
{
    use InsertionReferenceTrait;
    #[Route(name: 'app_emploie_ressource_index', methods: ['GET'])]
    public function index(RessourceRepository $ressourceRepository): Response
    {
        return $this->render('emploie/ressource/index.html.twig', [
            'ressources' => $ressourceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_emploie_ressource_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, OperationEmploieRepository
    $operationEmploieRepository,RessourceRepository $ressourceRepository,UniqueIdentifierGenerator $uniqueIdentifierGenerator): Response
    {
        $ressource = new Ressource();
        $ressource->setMois((int) date('n'));
        $ressource->setAnnee((int) date('Y'));

        $form = $this->createForm(RessourceType::class, $ressource);
        $total = $operationEmploieRepository->getTotalMontantAPayer();
        $totalPris = $ressourceRepository->getTotalMontantPris();
        $sommes = $operationEmploieRepository->getTotalRetenue();
//        $reste = $sommes['difference'] - $totalPris;
        $reste = $total - $totalPris;

        $form->get('totalMontant')->setData($reste);

        $identifier = $uniqueIdentifierGenerator->generateUniqueIdentifier(Ressource::class, 'referenceSysteme', 'RSC');

        $form->get('referenceManuel')->setData($identifier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->insertion($identifier,$ressource,$form);
            $entityManager->persist($ressource);
            $entityManager->flush();

            return $this->redirectToRoute('app_emploie_operations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('emploie/ressource/new.html.twig', [
            'ressource' => $ressource,
            'form' => $form,
            'totalRessource' => $reste
        ]);
    }

    #[Route('/{id}', name: 'app_emploie_ressource_show', methods: ['GET'])]
    public function show(Ressource $ressource): Response
    {
        return $this->render('emploie/ressource/show.html.twig', [
            'ressource' => $ressource,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_emploie_ressource_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ressource $ressource, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RessourceType::class, $ressource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_emploie_ressource_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('emploie/ressource/edit.html.twig', [
            'ressource' => $ressource,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emploie_ressource_delete', methods: ['POST'])]
    public function delete(Request $request, Ressource $ressource, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ressource->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($ressource);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_emploie_ressource_index', [], Response::HTTP_SEE_OTHER);
    }
}
