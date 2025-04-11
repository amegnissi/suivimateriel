<?php

namespace App\Controller;

use App\Repository\AffectationRepository;
use App\Repository\AssuranceRepository;
use App\Repository\EmployeRepository;
use App\Repository\MaintenanceRepository;
use App\Repository\MaterielRepository;
use App\Repository\TypeMaterielRepository;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(Request $request, MaterielRepository $materielRepository,
                          AffectationRepository $affectationRepository, MaintenanceRepository $maintenanceRepository,
                          AssuranceRepository $assuranceRepository, TypeMaterielRepository $typeMaterielRepository,PaginatorInterface $paginator):
    Response
    {
        // Récupération des statistiques

        $totalMateriels = $materielRepository->count([]);
        $totalAffectations = $affectationRepository->count([]);
        $totalMaintenances = $maintenanceRepository->count([]);

        // Récupérer uniquement les maintenances de type "Vidange"
        $vidanges = $maintenanceRepository->findBy(['typeMaintenance' => 'Vidange'], ['dateIntervention' => 'DESC'], 5);

        $maintenancesRecentes = $maintenanceRepository->findBy([], ['dateIntervention' => 'DESC'], 5);

        $orders = [];
        $assurances = $assuranceRepository->findAssurancesExpirant(30);
         $endTvm =  $assuranceRepository->findAssurancesExpirantParTypes(30,'tvm');
        $endAssurance =   $assuranceRepository->findAssurancesExpirantParTypes(30,'assurance');
        $endVisite =   $assuranceRepository->findAssurancesExpirantParTypes(30,'visite_technique');
            foreach ($assurances as $assurance) {
                $dateExpiration = new \DateTime();
                if ($assurance->getTypeAssurance() == 'assurance' ){
                    $dateExpiration = $assurance->getdateAssuranceFin();
                } elseif ($assurance->getTypeAssurance() == 'visite_technique' ){
                    $dateExpiration = $assurance->getdateVisiteTechniqueFin();
                } elseif ($assurance->getTypeAssurance() == 'tvm' ){
                    $dateExpiration = $assurance->getdateTVMFin();
                }
                $orders[] = [
//                'nature' => $maintenance->getTypeMaintenance(),
                    'title' =>  $assurance->getTypeAssurance(),
                    'date' =>  $dateExpiration,
                    'vehicule' =>  $assurance->getMateriel()->getMarque()->getLibelle() ." ". $assurance->getMateriel
                        ()->getImmatriculation(),
                    'icon' => 'construction', // Icône Material Symbols (peut être changé selon le type de maintenance)
                    'color' => 'danger' // Tu peux changer la couleur dynamiquement selon le type d'intervention
                ];
            }
        foreach ($maintenancesRecentes as $maintenance) {
            $orders[] = [
//                'nature' => $maintenance->getTypeMaintenance(),
                'title' =>  $maintenance->getTypeMaintenance(),
                'date' => $maintenance->getDateIntervention(),
                'vehicule' =>  $maintenance->getMateriel()->getMarque()->getLibelle() ." ". $maintenance->getMateriel
                    ()->getImmatriculation(),
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

        $query = $typeMaterielRepository->createQueryBuilder('e')
            ->getQuery();

        // Paginer les résultats
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Page actuelle
            10 // Nombre d'éléments par page
        );
        $cout = $maintenanceRepository->getCoutMaintenanceParMois();
        $labels = [];
        $values = [];

        $formatter = new \IntlDateFormatter(
            'fr_FR',
            \IntlDateFormatter::LONG,
            \IntlDateFormatter::NONE,
            null,
            null,
            'MMMM yyyy'
        );

        foreach ($cout as $item) {
            $date = \DateTime::createFromFormat('!Y-n', $item['annee'] . '-' . $item['mois']);
            $labels[] = $formatter->format($date);
            $values[] = $item['totalCout'];
        }
        return $this->render('dashboard.html.twig', [

            'totalMateriels' => $totalMateriels,
            'totalAffectations' => $totalAffectations,
            'totalMaintenances' => $totalMaintenances,
            'vidanges' => $vidanges,
            'orders' => $orders,
            'maintenances' => json_encode($maintenances),
            'affectations' => json_encode($affectations),
            'materiels' => json_encode($materiels),
            'pagination' => $pagination,
            'TVM' =>$endTvm,
            'VISITE' =>$endVisite,
            'ASSURANCE' =>$endAssurance,
            'labels' => json_encode($labels),
            'values' => json_encode($values),
        ]);
    }
}
