<?php

namespace App\Controller;


use App\Entity\Employe;
use App\Entity\DepartEmploye;
use App\Form\DepartEmployeType;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DepartEmployeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/depart-employe')]
class DepartEmployeController extends BaseController
{
    #[Route('/', name: 'depart_employe_index', methods: ['GET'])]
    public function index(Request $request, DepartEmployeRepository $departEmployeRepository, PaginatorInterface $paginator, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        $query = $departEmployeRepository->createQueryBuilder('e')
            ->getQuery();

        // Paginer les résultats
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Page actuelle
            10 // Nombre d'éléments par page
        );

        return $this->render('depart_employe/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/depart/new', name: 'depart_employe_create', methods: ['GET', 'POST'])]
    public function createDepart(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        $depart = new DepartEmploye();
        $form = $this->createForm(DepartEmployeType::class, $depart);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $employe = $depart->getEmploye();
    
            // Vérifier si l'employé a déjà un départ enregistré
            if ($employe && $employe->getDepartEmploye()) {
                $this->addFlash('warning', 'Cet employé a déjà un départ enregistré.');
                return $this->redirectToRoute('depart_employe_create');
            }

            // Vérifier si l'employé a des affectations actives
            $affectations = $employe->getAffectations();
            if (count($affectations) > 0) {
                $this->addFlash('warning', 'Cet employé est encore affecté à un. Veuillez dissocier son affectation avant de procéder à son départ.');
                return $this->redirectToRoute('affectations_index');  // Redirige vers la page de gestion des affectations
            }
    
            // Mise à jour du champ depart dans la table employe
            $employe->setDepart(true);
            $entityManager->persist($employe);
    
            // Enregistrement du départ
            $entityManager->persist($depart);
            $entityManager->flush();
    
            $this->addFlash('success', 'Départ enregistré avec succès.');
            return $this->redirectToRoute('depart_employe_index');
        }
    
        return $this->render('depart_employe/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'depart_employe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DepartEmploye $departEmploye, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        $form = $this->createForm(DepartEmployeType::class, $departEmploye);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Départ modifié avec succès.');
            return $this->redirectToRoute('depart_employe_index');
        }

        return $this->render('depart_employe/edit.html.twig', [
            'form' => $form->createView(),
            'departEmploye' => $departEmploye,
        ]);
    }

    #[Route('/{id}/cancel', name: 'depart_employe_delete', methods: ['POST'])]
    public function delete(Request $request, DepartEmploye $departEmploye, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        if ($this->isCsrfTokenValid('delete' . $departEmploye->getId(), $request->request->get('_token'))) {
            // Récupérer l'employé associé
            $employe = $departEmploye->getEmploye();
    
            if ($employe) {
                // Dissocier l'employé du départ pour éviter la suppression en cascade
                $employe->setDepartEmploye(null);
                $employe->setDepart(null);  
                $entityManager->persist($employe);
            }

            // Supprimer seulement l'entrée dans DepartEmploye
            $entityManager->remove($departEmploye);
            $entityManager->flush();
            
            //dd($employe);
    
            $this->addFlash('success', 'Départ annulé avec succès.');
        }
    
        return $this->redirectToRoute('depart_employe_index');
    }
}