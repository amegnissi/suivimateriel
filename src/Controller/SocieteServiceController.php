<?php

namespace App\Controller;

use App\Entity\SocieteService;
use App\Form\SocieteServiceType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\SocieteServiceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/societe_service')]
class SocieteServiceController extends BaseController
{
    /**
     * Liste des sociétés de services.
     */
    #[Route('/', name: 'societe_service_index', methods: ['GET'])]
    public function index(Request $request, SocieteServiceRepository $societeServiceRepository, PaginatorInterface $paginator, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        $query = $societeServiceRepository->createQueryBuilder('e')
            ->getQuery();

        // Paginer les résultats
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Page actuelle
            10 // Nombre d'éléments par page
        );

        return $this->render('societe_service/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * Formulaire de création d'une société de service.
     */
    #[Route('/new', name: 'societe_service_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        $societeService = new SocieteService();
        $form = $this->createForm(SocieteServiceType::class, $societeService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($societeService);
            $entityManager->flush();

            $this->addFlash('success', 'Société de service ajoutée avec succès.');
            return $this->redirectToRoute('societe_service_index');
        }

        return $this->render('societe_service/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Affichage des détails d'une société de service.
     */
    #[Route('/{id}', name: 'societe_service_show', methods: ['GET'])]
    public function show(SocieteService $societeService): Response
    {
        return $this->render('societe_service/show.html.twig', [
            'societe' => $societeService,
        ]);
    }

    /**
     * Formulaire d'édition d'une société de service.
     */
    #[Route('/{id}/edit', name: 'societe_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SocieteService $societeService, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        $form = $this->createForm(SocieteServiceType::class, $societeService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Société de service modifiée avec succès.');

            return $this->redirectToRoute('societe_service_index');
        }

        return $this->render('societe_service/edit.html.twig', [
            'form' => $form->createView(),
            'societe' => $societeService,
        ]);
    }

    /**
     * Suppression d'une société de service.
     */
    #[Route('/{id}', name: 'societe_service_delete', methods: ['POST'])]
    public function delete(Request $request, SocieteService $societeService, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        if ($societeService->getAffectations()->count() > 0) {
            $this->addFlash('error', 'Impossible de supprimer une société avec des affectations associées.');
            return $this->redirectToRoute('societe_service_index');
        }

        if ($this->isCsrfTokenValid('delete'.$societeService->getId(), $request->request->get('_token'))) {
            $entityManager->remove($societeService);
            $entityManager->flush();
            $this->addFlash('success', 'Société de service supprimée avec succès.');
        }

        return $this->redirectToRoute('societe_service_index');
    }
}