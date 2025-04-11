<?php

namespace App\Controller;

use App\Entity\TypeMaintenance;
use App\Form\TypeMaintenanceType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TypeMaintenanceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/types_maintenances')]
class TypeMaintenanceController extends BaseController
{
    #[Route('/', name: 'types_maintenances_index', methods: ['GET'])]
    public function index(Request $request, TypeMaintenanceRepository $typeMaintenanceRepository, PaginatorInterface $paginator, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        $query = $typeMaintenanceRepository->createQueryBuilder('e')
            ->getQuery();

        // Paginer les résultats
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Page actuelle
            10 // Nombre d'éléments par page
        );
        
        return $this->render('types_maintenances/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'types_maintenances_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        $typeMaintenance = new TypeMaintenance();
        $form = $this->createForm(TypeMaintenanceType::class, $typeMaintenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeMaintenance);
            $entityManager->flush();

            $this->addFlash('success', 'Type Maintenance ajouté avec succès.');
            return $this->redirectToRoute('types_maintenances_index');
        }

        return $this->render('types_maintenances/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'types_maintenances_show', methods: ['GET'])]
    public function show(TypeMaintenance $typeMaintenance): Response
    {
        return $this->render('types_maintenances/show.html.twig', [
            'typeMaintenance' => $typeMaintenance,
        ]);
    }

    #[Route('/{id}/edit', name: 'types_maintenances_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeMaintenance $typeMaintenance, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        $form = $this->createForm(TypeMaintenanceType::class, $typeMaintenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Type Maintenance mis à jour avec succès.');
            return $this->redirectToRoute('types_maintenances_index');
        }

        return $this->render('types_maintenances/edit.html.twig', [
            'form' => $form->createView(),
            'typeMaintenance' => $typeMaintenance,
        ]);
    }

    #[Route('/{id}/delete', name: 'types_maintenances_delete', methods: ['POST'])]
    public function delete(Request $request, TypeMaintenance $typeMaintenance, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        if ($this->isCsrfTokenValid('delete' . $typeMaintenance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($typeMaintenance);
            $entityManager->flush();
            $this->addFlash('success', 'Type Maintenance supprimé avec succès.');
        }

        return $this->redirectToRoute('types_maintenances_index');
    }
}