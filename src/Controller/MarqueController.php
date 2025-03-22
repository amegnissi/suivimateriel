<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Form\MarqueType;
use App\Repository\MarqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/marques')]
class MarqueController extends BaseController
{
    #[Route('/', name: 'marques_index', methods: ['GET'])]
    public function index(Request $request, MarqueRepository $marqueRepository, PaginatorInterface $paginator, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        $query = $marqueRepository->createQueryBuilder('e')
            ->getQuery();

        // Paginer les résultats
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Page actuelle
            10 // Nombre d'éléments par page
        );

        return $this->render('marques/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/marques/new', name: 'marques_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        $marque = new Marque();
        $form = $this->createForm(MarqueType::class, $marque);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($marque);
            $entityManager->flush();

            $this->addFlash('success', 'Marque ajoutée avec succès.');

            return $this->redirectToRoute('marques_index');
        }

        return $this->render('marques/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'marques_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Marque $marque, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        $form = $this->createForm(MarqueType::class, $marque);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Marque mise à jour avec succès.');

            return $this->redirectToRoute('marques_index');
        }

        return $this->render('marques/edit.html.twig', [
            'form' => $form->createView(),
            'marque' => $marque,
        ]);
    }

    #[Route('/{id}', name: 'marques_delete', methods: ['POST'])]
    public function delete(Request $request, Marque $marque, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        if ($marque->getMateriels()->count() > 0) {
            $this->addFlash('error', 'Impossible de supprimer une marque associée à des matériels.');

            return $this->redirectToRoute('marques_index');
        }

        if ($this->isCsrfTokenValid('delete'.$marque->getId(), $request->request->get('_token'))) {
            $entityManager->remove($marque);
            $entityManager->flush();
            $this->addFlash('success', 'Marque supprimé avec succès.');
        }

        return $this->redirectToRoute('marques_index');
    }

    #[Route('/{id}/materiels', name: 'marques_materiels', methods: ['GET'])]
    public function viewMateriels(Marque $marque): Response
    {
        $materiels = $marque->getMateriels();

        return $this->render('marques/materiels.html.twig', [
            'marque' => $marque,
            'materiels' => $materiels,
        ]);
    }
}