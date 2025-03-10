<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Form\MarqueType;
use App\Repository\MarqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/marques')]
class MarqueController extends AbstractController
{
    #[Route('/', name: 'marques_index', methods: ['GET'])]
    public function index(Request $request, MarqueRepository $marqueRepository): Response
    {
        $search = $request->query->get('search');
        $estVehicule = $request->query->get('est_vehicule');

        $queryBuilder = $marqueRepository->createQueryBuilder('m');

        if ($search) {
            $queryBuilder->andWhere('m.nom LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        if ($estVehicule !== null) {
            $queryBuilder->andWhere('m.estVehicule = :estVehicule')
                ->setParameter('estVehicule', (bool) $estVehicule);
        }

        $marques = $queryBuilder->getQuery()->getResult();

        return $this->render('marques/index.html.twig', [
            'marques' => $marques,
        ]);
    }

    #[Route('/marques/new', name: 'marques_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
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