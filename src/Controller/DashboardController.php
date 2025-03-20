<?php

namespace App\Controller;

use App\Repository\AffectationRepository;
use App\Repository\EmployeRepository;
use App\Repository\MaintenanceRepository;
use App\Repository\MaterielRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(EmployeRepository $employeRepository, MaterielRepository $materielRepository, AffectationRepository $affectationRepository, MaintenanceRepository $maintenanceRepository): Response
    {
        // Récupération des statistiques
        $totalEmployes = $employeRepository->count([]);
        $totalMateriels = $materielRepository->count([]);
        $totalAffectations = $affectationRepository->count([]);
        $totalMaintenances = $maintenanceRepository->count([]);

        // Récupérer uniquement les maintenances de type "Vidange"
        $vidanges = $maintenanceRepository->findBy(['typeMaintenance' => 'Vidange'], ['dateIntervention' => 'DESC'], 5);

        $maintenancesRecentes = $maintenanceRepository->findBy([], ['dateIntervention' => 'DESC'], 5);

        $orders = [];

        foreach ($maintenancesRecentes as $maintenance) {
            $orders[] = [
                'title' => 'Maintenance : ' . $maintenance->getTypeMaintenance(),
                'date' => $maintenance->getDateIntervention()?->format('d/m/Y'),
                'icon' => 'construction', // Icône Material Symbols (peut être changé selon le type de maintenance)
                'color' => 'warning' // Tu peux changer la couleur dynamiquement selon le type d'intervention
            ];
        }

        // Récupérer les maintenances par mois
        $maintenances = $maintenanceRepository->countByMonth();
    
        // Récupérer les affectations par mois
        $affectations = $affectationRepository->countByMonth();

        // Récupérer les matériels par type
        $materiels = $materielRepository->countByType();

        return $this->render('dashboard.html.twig', [
            'totalEmployes' => $totalEmployes,
            'totalMateriels' => $totalMateriels,
            'totalAffectations' => $totalAffectations,
            'totalMaintenances' => $totalMaintenances,
            'vidanges' => $vidanges,
            'orders' => $orders,
            'maintenances' => json_encode($maintenances),
            'affectations' => json_encode($affectations),
            'materiels' => json_encode($materiels),
        ]);
    }
}
