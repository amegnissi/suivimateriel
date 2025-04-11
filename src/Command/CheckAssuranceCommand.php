<?php

namespace App\Command;

use App\Entity\Assurance;
use App\Entity\Entreprise;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:check-assurance',
    description: 'Vérifie les échéances des assurances, TVM et visites techniques et génère des notifications.'
)]
class CheckAssuranceCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private NotificationService $notificationService;

    public function __construct(EntityManagerInterface $entityManager, NotificationService $notificationService)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->notificationService = $notificationService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $today = new \DateTime();
        $entreprise = $this->entityManager->getRepository(Entreprise::class)->find(1);

        if (!$entreprise) {
            $output->writeln('<error>Aucune entreprise trouvée.</error>');
            return Command::FAILURE;
        }

        // Récupérer les délais de rappel définis dans l'entreprise
        $delaiAssurance = $entreprise->getDelaiAssurance() ?? 7; // Par défaut 7 jours si non défini
        $delaiTVM = $entreprise->getDelaiTVM() ?? 7;
        $delaiVisiteTechnique = $entreprise->getDelaiVisiteTechnique() ?? 7;

        // Récupérer toutes les assurances
        $assurances = $this->entityManager->getRepository(Assurance::class)->findAll();

        foreach ($assurances as $assurance) {
            switch ($assurance->getTypeAssurance()) {
                case 'assurance':
                    $this->checkDate($assurance->getDateAssuranceFin(), 'Assurance', $assurance, $delaiAssurance);
                    break;
                case 'tvm':
                    $this->checkDate($assurance->getDateTVMFin(), 'TVM', $assurance, $delaiTVM);
                    break;
                case 'visite_technique':
                    $this->checkDate($assurance->getDateVisiteTechniqueFin(), 'Visite technique', $assurance, $delaiVisiteTechnique);
                    break;
            }
        }

        $output->writeln('<info>Vérification des échéances terminée.</info>');
        return Command::SUCCESS;
    }

    private function checkDate(?\DateTimeInterface $dateFin, string $type, Assurance $assurance, int $delaiRappel): void
    {
        if (!$dateFin || $assurance->getNotifEnvoyee()) {
            return;
        }
    
        $materiel = $assurance->getMateriel();
        $marque = $materiel->getMarque() ? $materiel->getMarque()->getLibelle() : 'Marque inconnue';
        $immatriculation = $materiel->getImmatriculation() ?? 'Sans immatriculation';
    
        $nomVehicule = "$marque - $immatriculation";
    
        $today = new \DateTime();
        $interval = $today->diff($dateFin);
        $daysRemaining = (int) $interval->format('%r%a'); // Nombre de jours restants
    
        if ($daysRemaining <= 0) {
            $message = "🚨 L'échéance de $type pour le véhicule '$nomVehicule' est dépassée !";
            $this->notificationService->createNotification($message);
            $assurance->setNotifEnvoyee(true);
        } elseif ($daysRemaining <= $delaiRappel) {
            $message = "⚠️ L'échéance de $type pour le véhicule '$nomVehicule' arrive à échéance dans $daysRemaining jour(s) !";
            $this->notificationService->createNotification($message);
            $assurance->setNotifEnvoyee(true);
        }
    
        $this->entityManager->flush();
    }
}
