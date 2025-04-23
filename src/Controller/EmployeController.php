<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use App\Entity\Entreprise;
use App\Service\ExportService;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/employes')]
class EmployeController extends BaseController
{
    #[Route('/', name: 'employes_index', methods: ['GET'])]
    public function index(Request $request, EmployeRepository $employeRepository, PaginatorInterface $paginator, EntityManagerInterface  $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        $query = $employeRepository->createQueryBuilder('e')
            ->where('e.depart IS NULL OR e.depart = :depart')
            ->setParameter('depart', false)
            ->getQuery();

        // Paginer les résultats
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Page actuelle
            10 // Nombre d'éléments par page
        );

        return $this->render('employes/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/export-excel', name: 'employes_export_excel')]
    public function exportExcel(EmployeRepository $employeRepository, ExportService $exportService): Response
    {
        $employes = $employeRepository->createQueryBuilder('e')
            ->where('e.depart IS NULL OR e.depart = :depart')
            ->setParameter('depart', false)
            ->getQuery()
            ->getResult();
    
        // Définir les en-têtes du fichier Excel
        $headers = ['N°', 'Nom', 'Prénom', 'Poste', 'Téléphone Pro', 'Téléphone Perso', 'Statut'];
    
        // Préparer les données à exporter
        $data = [];
        foreach ($employes as $index => $employe) {
            $data[] = [
                $index + 1,
                $employe->getNom(),
                $employe->getPrenom(),
                $employe->getPoste() ? $employe->getPoste()->getLibelle() : 'Non défini',
                $employe->getTelephoneCorporate() ?: 'N/A',
                $employe->getTelephonePersonnel() ?: 'N/A',
                count($employe->getAffectations()) > 0 ? 'Affecté' : 'Non Affecté',
            ];
        }
    
        // Utilisation du service ExportService
        return $exportService->exportExcel($data, $headers, 'employes.xlsx');
    }

    #[Route('/export-pdf', name: 'employes_export_pdf')]
    public function exportPdf(EmployeRepository $employeRepository, ExportService $exportService): Response
    {    
        $employes = $employeRepository->createQueryBuilder('e')
            ->where('e.depart IS NULL OR e.depart = :depart')
            ->setParameter('depart', false)
            ->getQuery()
            ->getResult();
    
        // Utilisation du service ExportService
        return $exportService->exportPdf('employes/export_pdf.html.twig', [
            'employes' => $employes
        ], 'employes.pdf');
    }
    
    #[Route('/new', name: 'employes_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }

        $employe = new Employe();
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photoFile = $form->get('photoFile')->getData();
            if ($photoFile) {
                $newFilename = uniqid().'.'.$photoFile->guessExtension();
                $photoFile->move($this->getParameter('uploads_directory').'/employes', $newFilename);
                $employe->setPhoto($newFilename);
            }

            $copieCarteIdFile = $form->get('copieCarteIdFile')->getData();
            if ($copieCarteIdFile) {
                $newFilename = uniqid().'.'.$copieCarteIdFile->guessExtension();
                $copieCarteIdFile->move($this->getParameter('uploads_directory').'/employes', $newFilename);
                $employe->setCopieCarteId($newFilename);
            }

            $copieDiplomeFile = $form->get('copieDiplomeFile')->getData();
            if ($copieDiplomeFile) {
                $newFilename = uniqid().'.'.$copieDiplomeFile->guessExtension();
                $copieDiplomeFile->move($this->getParameter('uploads_directory').'/employes', $newFilename);
                $employe->setCopieDiplome($newFilename);
            }

            $certificatAcquiteVisuelFile = $form->get('certificatAcquiteVisuelFile')->getData();
            if ($certificatAcquiteVisuelFile) {
                $newFilename = uniqid().'.'.$certificatAcquiteVisuelFile->guessExtension();
                $certificatAcquiteVisuelFile->move($this->getParameter('uploads_directory').'/employes', $newFilename);
                $employe->setCertificatAcquiteVisuel($newFilename);
            }

            // Associer l'employé à l'entreprise
            $entreprise = $entityManager->getRepository(Entreprise::class)->findOneBy([]);
            $employe->setEntreprise($entreprise);
        
            $entityManager->persist($employe);
            $entityManager->flush();

            $this->addFlash('success', 'Employé enregistré avec succès.');
            return $this->redirectToRoute('employes_index');
        }

        return $this->render('employes/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'employes_show', methods: ['GET'])]
    public function show(Employe $employe): Response
    {
        return $this->render('employes/show.html.twig', [
            'employe' => $employe,
        ]);
    }

    #[Route('/{id}/edit', name: 'employes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Employe $employe, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }

        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Employé modifié avec succès.');
            return $this->redirectToRoute('employes_index');
        }

        return $this->render('employes/edit.html.twig', [
            'form' => $form->createView(),
            'employe' => $employe,
        ]);
    }

    #[Route('/{id}', name: 'employes_delete', methods: ['POST'])]
    public function delete(Request $request, Employe $employe, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }

        if ($this->isCsrfTokenValid('delete' . $employe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($employe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('employes_index');
    }
}