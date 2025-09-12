<?php

namespace App\Controller\Emploie;

use App\Entity\Emploie\Ressource;
use App\Form\Emploie\RessourceType;
use App\Repository\Emploie\OperationEmploieRepository;
use App\Repository\Emploie\ExerciceRepository;
use App\Repository\Emploie\MoisRepository;
use App\Repository\Emploie\PeriodeRepository;
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
    $operationEmploieRepository,RessourceRepository $ressourceRepository,UniqueIdentifierGenerator $uniqueIdentifierGenerator,PeriodeRepository $periodeRepository,MoisRepository $moisRepository,ExerciceRepository $exerciceRepository): Response
    {
        $session = $request->getSession();
        $periode = $session->get('selected_periode_id');
        $p =  $periodeRepository->find($periode);
        $ressource = new Ressource();
        $ressource->setMois((int) $p->getMois()->getId());
        $ressource->setAnnee((int) $p->getExercice()->getAnnee());

        $form = $this->createForm(RessourceType::class, $ressource);
        $total = $operationEmploieRepository->getTotalMontantAPayer($periode);
        $totalPris = $ressourceRepository->getTotalMontantPris($periode);
        $sommes = $operationEmploieRepository->getTotalRetenue($periode);
//        $reste = $sommes['difference'] - $totalPris;
        $reste =  $totalPris - $total;

        $form->get('totalMontant')->setData(( $reste));
       $form->get('totalEmploie')->setData($total);
        $form->get('totalRessources')->setData(abs($totalPris));

        $identifier = $uniqueIdentifierGenerator->generateUniqueIdentifier(Ressource::class, 'referenceSysteme', 'RSC');

        $form->get('referenceManuel')->setData($identifier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $montantAppro = $form->get('montantPris')->getData();
            $reste =  ($totalPris +  $montantAppro  ) - $total;
//            dd($reste);
            $this->insertion($identifier,$ressource,$form);
            $ressource->setPeriode( $p);
            $ressource->setMontantRestant( $reste);
            $entityManager->persist($ressource);
            $entityManager->flush();

            return $this->redirectToRoute('app_emploie_operations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('emploie/ressource/new.html.twig', [
            'ressource' => $ressource,
            'form' => $form,
            'totalRessource' =>abs( $reste)
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
