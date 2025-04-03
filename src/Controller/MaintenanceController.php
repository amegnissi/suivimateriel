<?php

namespace App\Controller;

use App\Entity\Maintenance;
use App\Form\MaintenanceType;
use App\Service\ExportService;
use App\Repository\MaterielRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AffectationRepository;
use App\Repository\MaintenanceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/maintenances')]
class MaintenanceController extends BaseController
{
    #[Route('/', name: 'maintenances_index', methods: ['GET'])]
    public function index(Request $request, MaintenanceRepository $maintenanceRepository, PaginatorInterface $paginator, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        $query = $maintenanceRepository->createQueryBuilder('e')
            ->getQuery();

        // Paginer les résultats
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Page actuelle
            10 // Nombre d'éléments par page
        );

        return $this->render('maintenances/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/export-excel', name: 'maintenances_export_excel')]
    public function exportExcel(MaintenanceRepository $maintenanceRepository, ExportService $exportService): Response
    {
        // Récupérer les maintenances
        $maintenances = $maintenanceRepository->findAll();

        // Définir les en-têtes du fichier Excel
        $headers = [
            'N°', 'Matériel', 'Type', 'Date d\'Intervention', 'Km Actuel', 'Km Prochain', 'Statut'
        ];

        // Préparer les données à exporter
        $data = [];
        foreach ($maintenances as $index => $maintenance) {
            $data[] = [
                $index + 1,
                $maintenance->getMateriel() ? $maintenance->getMateriel()->getMarque()->getLibelle() . ' - ' . $maintenance->getMateriel()->getImmatriculation() : 'N/A',
                $maintenance->getTypeMaintenance() ?? 'N/A',
                $maintenance->getDateIntervention() ? $maintenance->getDateIntervention()->format('d/m/Y') : 'N/A',
                $maintenance->getKilometrageActuel() ? number_format($maintenance->getKilometrageActuel(), 0, ',', ' ') . ' km' : '-',
                $maintenance->getKilometragePrevisionnel() ? number_format($maintenance->getKilometragePrevisionnel(), 0, ',', ' ') . ' km' : '-',
                $maintenance->getStatut() == 0 ? 'En cours' : 'Terminé',
            ];
        }

        // Utilisation du service ExportService
        return $exportService->exportExcel($data, $headers, 'maintenances.xlsx');
    }

    #[Route('/export-pdf', name: 'maintenances_export_pdf')]
    public function exportPdf(MaintenanceRepository $maintenanceRepository, ExportService $exportService): Response
    {
        // Récupérer les maintenances
        $maintenances = $maintenanceRepository->findAll();

        // Utilisation du service ExportService pour exporter en PDF
        return $exportService->exportPdf('maintenances/export_pdf.html.twig', [
            'maintenances' => $maintenances
        ], 'maintenances.pdf');
    }

    #[Route('/new', name: 'maintenances_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, MaterielRepository $materielRepository): Response 
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        $maintenance = new Maintenance();
        $form = $this->createForm(MaintenanceType::class, $maintenance);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $materiel = $maintenance->getMateriel();
            if (!$materiel) {
                $this->addFlash('error', 'Veuillez sélectionner un matériel.');
                return $this->redirectToRoute('maintenances_create');
            }
    
            // Vérifier l'entreprise et le kilométrage
            $entreprise = $materiel->getEntreprise();
            if ($entreprise) {
                $kilometrageActuel = $maintenance->getKilometrageActuel();
                $kilometrageIntervalle = $entreprise->getKilometrage();
    
                if ($kilometrageActuel !== null && $kilometrageIntervalle !== null) {
                    $maintenance->setKilometragePrevisionnel($kilometrageActuel + $kilometrageIntervalle);
                }
            }
    
            // Mettre le matériel en maintenance
            $materiel->setStatut(2); // 2 = En maintenance
            $entityManager->persist($materiel);
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

    #[Route('/{id}/fisnish', name: 'maintenances_finish', methods: ['POST'])]
    public function finishMaintenance(Maintenance $maintenance, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        $materiel = $maintenance->getMateriel();
        if ($materiel) {
            $materiel->setStatut(1); // 1 = Disponible après maintenance
            $entityManager->persist($materiel);
        }
        $maintenance->setStatut(1);
        
        $entityManager->flush();

        $this->addFlash('success', 'Maintenance terminée et matériel remis en service.');
        return $this->redirectToRoute('maintenances_index');
    }
}