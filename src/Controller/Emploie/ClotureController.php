<?php

declare(strict_types=1);

namespace App\Controller\Emploie;

use App\Repository\Emploie\OperationEmploieRepository;
use App\Repository\Emploie\PeriodeRepository;
use App\Repository\Emploie\RessourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ClotureController extends AbstractController
{
    #[Route('/liste-operations-cloture', name: 'app_emploie_demandes_autorisations_index', methods: ['GET'])]
    public function index(PeriodeRepository $periodeRepository,RessourceRepository $ressourceRepository,
                          OperationEmploieRepository
    $operationEmploieRepository): Response
    {
        $excerciesCloturees = $periodeRepository->findBy(['isCloture'=>true]);
        $grouped = [];
        foreach ($excerciesCloturees as $excercice) {
            $annee = $excercice->getExercice()->getAnnee();
            if (!isset($grouped[$annee])) {
                $grouped[$annee] = [];
            }
            $grouped[$annee][] = $excercice;
        }
        return $this->render('emploie/cloture/liste_excercice_cloture.html.twig', [
            'excerciesCloturees' =>$grouped
        ]);
    }

    #[Route('/operations-cloture', name: 'app_emploie_demandes_cloture', methods: ['GET', 'POST'])]
    public function clotureOperations(Request                    $request, RessourceRepository $ressourceRepository, PeriodeRepository $periodeRepository,
                                      OperationEmploieRepository $operationEmploieRepository, EntityManagerInterface $entityManager): JsonResponse
    {

        try {
            $data = json_decode($request->getContent(), true);
//            dd($data);
//            dd($data['periodeId']);
            $periode = $periodeRepository->find((int)$data['periodeId']);

            if ($periode) {
                $periode->setIsCloture(true);
                $entityManager->persist($periode);

                foreach ($data['ressources'] as $itemData) {
                    $ressource = $ressourceRepository->find($itemData['id']);
                    $ressource->setIsClotured(true);
                    $entityManager->persist($ressource);
                }

                foreach ($data['emplois'] as $itemData) {
                    $operationEmploie = $operationEmploieRepository->find($itemData['id']);
                    $operationEmploie->setIsClotured(true);
                    $entityManager->persist($operationEmploie);
                }
                $entityManager->flush();
            }
            return new JsonResponse([
                'success' => true,
                'message' => 'Commande enregistrée avec succès',
                'redirect_url' => $this->generateUrl('app_emploie_demandes_autorisations_index')
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Invalid JSON',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
