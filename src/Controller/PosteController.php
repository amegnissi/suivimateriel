<?php

namespace App\Controller;

use App\Entity\Poste;
use App\Form\PosteType;
use App\Repository\PosteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/postes')]
class PosteController extends AbstractController
{
    /**
     * Liste des postes.
     */
    #[Route('/', name: 'postes_index', methods: ['GET'])]
    public function index(PosteRepository $posteRepository): Response
    {
        $postes = $posteRepository->findAll();
        return $this->render('postes/index.html.twig', [
            'postes' => $postes,
        ]);
    }

     /**
     * Formulaire de création d'un poste.
     */
    #[Route('/new', name: 'postes_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $poste = new Poste();
        $form = $this->createForm(PosteType::class, $poste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($poste);
            $entityManager->flush();

            $this->addFlash('success', 'Poste ajouté avec succès.');
            return $this->redirectToRoute('postes_index');
        }

        return $this->render('postes/create.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Affichage des détails d'un poste.
     */
    #[Route('/{id}', name: 'postes_show', methods: ['GET'])]
    public function show(Poste $poste): Response
    {
        return $this->render('postes/show.html.twig', [
            'poste' => $poste,
        ]);
    }

    /**
     * Formulaire d'édition d'un poste.
     */
    #[Route('/{id}/edit', name: 'postes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Poste $poste, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PosteType::class, $poste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Poste modifié avec succès.');

            return $this->redirectToRoute('postes_index');
        }

        return $this->render('postes/edit.html.twig', [
            'form' => $form->createView(),
            'poste' => $poste,
        ]);
    }

    /**
     * Suppression d'un poste.
     */
    #[Route('/{id}', name: 'postes_delete', methods: ['POST'])]
    public function delete(Request $request, Poste $poste, EntityManagerInterface $entityManager): Response
    {
        if ($poste->getEmploye()->count() > 0) {
            $this->addFlash('error', 'Impossible de supprimer un poste avec des employés associés.');
            return $this->redirectToRoute('postes_index');
        }

        if ($this->isCsrfTokenValid('delete'.$poste->getId(), $request->request->get('_token'))) {
            $entityManager->remove($poste);
            $entityManager->flush();
            $this->addFlash('success', 'Poste supprimé avec succès.');
        }

        return $this->redirectToRoute('postes_index');
    }
}