<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\Affectation;
use App\Form\AffectationType;
use App\Service\ExportService;
use App\Repository\EmployeRepository;
use App\Repository\MaterielRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AffectationRepository;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\SocieteServiceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/affectations')]
class AffectationController extends BaseController
{
     /**
     * Liste des affectations.
     */
    #[Route('/', name: 'affectations_index', methods: ['GET'])]
    public function index(Request $request, AffectationRepository $affectationRepository, PaginatorInterface $paginator, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        $query = $affectationRepository->createQueryBuilder('e')
            ->getQuery();

        // Paginer les résultats
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Page actuelle
            10 // Nombre d'éléments par page
        );

        return $this->render('affectations/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/export-excel', name: 'affectations_export_excel')]
    public function exportExcel(AffectationRepository $affectationRepository, ExportService $exportService): Response
    {
        // Récupérer les affectations
        $affectations = $affectationRepository->findAll();

        // Définir les en-têtes du fichier Excel
        $headers = [
            'N°', 'Matériel', 'Employé', 'Société', 'Date d\'Affectation'
        ];

        // Préparer les données à exporter
        $data = [];
        foreach ($affectations as $index => $affectation) {
            $data[] = [
                $index + 1,
                $affectation->getMateriel() ? $affectation->getMateriel()->getMarque()->getLibelle() . ' - ' . $affectation->getMateriel()->getImmatriculation() : 'N/A',
                $affectation->getEmploye() ? $affectation->getEmploye()->getPrenom() . ' ' . $affectation->getEmploye()->getNom() : 'N/A',
                $affectation->getSociete() ? $affectation->getSociete()->getNom() : 'N/A',
                $affectation->getDateAffectation() ? $affectation->getDateAffectation()->format('d/m/Y') : 'N/A'
            ];
        }

        // Utilisation du service ExportService
        return $exportService->exportExcel($data, $headers, 'affectations.xlsx');
    }

    #[Route('/export-pdf', name: 'affectations_export_pdf')]
    public function exportPdf(AffectationRepository $affectationRepository, ExportService $exportService): Response
    {
        // Récupérer les affectations
        $affectations = $affectationRepository->findAll();

        // Utilisation du service ExportService pour exporter en PDF
        return $exportService->exportPdf('affectations/export_pdf.html.twig', [
            'affectations' => $affectations
        ], 'affectations.pdf');
    }

    /**
     * Formulaire de création d'une affectation.
     */
    #[Route('/new', name: 'affectations_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response 
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
    
        $affectation = new Affectation();
        $form = $this->createForm(AffectationType::class, $affectation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Valider que le matériel et l'employé sont affectés correctement
            if ($affectation->getSociete() && !$affectation->getEmploye()) {
                $this->addFlash('error', 'L\'employé doit être affecté en même temps que le matériel à une société.');
                return $this->redirectToRoute('affectations_create');
            }
    
            // Récupérer les entités nécessaires
            $materiel = $affectation->getMateriel();
            $lieu = $form->get('lieuAffectation')->getData();
            $affectation->setLieuAffectation($lieu);
            $entreprise = $entityManager->getRepository(Entreprise::class)->findOneBy([]);
    
            if ($materiel) {
                // Mise à jour du statut et du lieu
                $materiel->setStatut(1);
                // $materiel->setLieuAffactation($lieu);
    
                // 🔐 Génération du code automatique
                $annee = (new \DateTime())->format('Y');
                $sigle = $entreprise ? $entreprise->getSigle() : 'ENT';
                $type = $materiel->getType() ? $materiel->getType()->getLibelle() : 'TYPE';
                $numero = str_pad((string) $materiel->getId(), 4, '0', STR_PAD_LEFT);
    
                // Selon type de lieu
                $categorie = $lieu && $lieu->getType() === 'interne' 
                    ? $lieu->getNom()
                    : ($affectation->getSociete() ? $affectation->getSociete()->getNom() : 'Externe');
    
                // Nettoyage
                $categorie = strtoupper(str_replace(' ', '_', $categorie));
                $type = strtoupper(str_replace(' ', '_', $type));
                $sigle = strtoupper($sigle);
    
                $code = "{$sigle}_{$annee}/{$categorie}/{$type}/{$numero}";
                $materiel->setCode($code);
    
                $entityManager->persist($materiel);
            }
    
            $entityManager->persist($affectation);
            $entityManager->flush();
    
            $this->addFlash('success', 'Affectation ajoutée avec succès.');
            return $this->redirectToRoute('affectations_index');
        }
    
        return $this->render('affectations/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Affichage des détails d'une affectation.
     */
    #[Route('/{id}', name: 'affectations_show', methods: ['GET'])]
    public function show(Affectation $affectation): Response
    {
        return $this->render('affectations/show.html.twig', [
            'affectation' => $affectation,
        ]);
    }

    /**
     * Formulaire de modification d'une affectation.
     */
    #[Route('/{id}/edit', name: 'affectations_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Affectation $affectation, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        $form = $this->createForm(AffectationType::class, $affectation);
        $form->handleRequest($request);

        // Préremplir le champ lieuAffectation si le matériel en a déjà un
        $materiel = $affectation->getMateriel();
        if ($materiel) {
            $form->get('lieuAffectation')->setData($materiel->getLieuAffactation());
        }

        if ($form->isSubmitted() && $form->isValid()) {
            // Mise à jour du lieu d'affectation du matériel
            $materiel = $affectation->getMateriel();
            if ($materiel) {
                $materiel->setLieuAffactation($form->get('lieuAffectation')->getData());
                $entityManager->persist($materiel);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Affectation modifiée avec succès.');

            return $this->redirectToRoute('affectations_index');
        }

        return $this->render('affectations/edit.html.twig', [
            'form' => $form->createView(),
            'affectation' => $affectation,
        ]);
    }

    #[Route('/{id}/delete', name: 'affectations_delete', methods: ['POST'])]
    public function delete(Request $request, Affectation $affectation, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        if ($this->isCsrfTokenValid('delete' . $affectation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($affectation);
            $entityManager->flush();

            $this->addFlash('success', 'Affectation supprimée avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('affectations_index');
    }

    #[Route('/{id}/cancel', name: 'affectations_cancel', methods: ['POST'])]
    public function dissocier(Request $request, Affectation $affectation, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        if ($this->isCsrfTokenValid('cancel' . $affectation->getId(), $request->request->get('_token'))) {
            // Réinitialiser les informations de l'affectation au lieu de la supprimer
            $affectation->setEmploye(null); // Dissocier l'employé
            $affectation->setSociete(null); // Dissocier la société
            $affectation->setLieuAffectation(null);

            // Mettre à jour le statut du matériel
            $materiel = $affectation->getMateriel();
            if ($materiel) {
                $materiel->setStatut(0); // Mettre le statut comme "non affecté"
                $entityManager->persist($materiel);
            }

            $entityManager->remove($affectation);
            $entityManager->flush();

            $this->addFlash('success', 'L\'affectation a été annulée avec succès.');
        } else {
            $this->addFlash('error', 'Échec de l\'annulation de l\'affectation.');
        }

        return $this->redirectToRoute('affectations_index');
    }

}