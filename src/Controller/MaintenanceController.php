<?php

namespace App\Controller;

use App\Entity\Maintenance;
use App\Form\MaintenanceType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AffectationRepository;
use App\Repository\MaintenanceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/maintenances')]
class MaintenanceController extends AbstractController
{
    #[Route('/', name: 'maintenances_index', methods: ['GET'])]
    public function index(MaintenanceRepository $maintenanceRepository): Response
    {
        return $this->render('maintenances/index.html.twig', [
            'maintenances' => $maintenanceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'maintenances_create', methods: ['GET', 'POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        AffectationRepository $affectationRepository
    ): Response {
        $maintenance = new Maintenance();
        $form = $this->createForm(MaintenanceType::class, $maintenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $affectation = $maintenance->getAffectation();
            if (!$affectation) {
                $this->addFlash('error', 'Une affectation doit être sélectionnée.');
                return $this->redirectToRoute('maintenances_create');
            }

            // Mettre à jour le statut du matériel si nécessaire
            $materiel = $affectation->getMateriel();
            if ($materiel) {
                $materiel->setStatut(2); // 2 = En maintenance
                $entityManager->persist($materiel);
            }

            $entityManager->persist($maintenance);
            $entityManager->flush();

            $this->addFlash('success', 'Maintenance enregistrée avec succès.');
            return $this->redirectToRoute('maintenances_index');
        }

        return $this->render('maintenances/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'maintenances_show', methods: ['GET'])]
    public function show(Maintenance $maintenance): Response
    {
        return $this->render('maintenances/show.html.twig', [
            'maintenance' => $maintenance,
        ]);
    }
}