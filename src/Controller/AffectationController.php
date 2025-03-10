<?php

namespace App\Controller;

use App\Entity\Affectation;
use App\Form\AffectationType;
use App\Repository\EmployeRepository;
use App\Repository\MaterielRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AffectationRepository;
use App\Repository\SocieteServiceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/affectations')]
class AffectationController extends AbstractController
{
     /**
     * Liste des affectations.
     */
    #[Route('/', name: 'affectations_index', methods: ['GET'])]
    public function index(AffectationRepository $affectationRepository): Response
    {
        $affectations = $affectationRepository->findAll();

        return $this->render('affectations/index.html.twig', [
            'affectations' => $affectations,
        ]);
    }

    /**
     * Formulaire de création d'une affectation.
     */
    #[Route('/new', name: 'affectations_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response {
        $affectation = new Affectation();
        $form = $this->createForm(AffectationType::class, $affectation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Valider que le matériel et l'employé sont affectés correctement
            if ($affectation->getSociete() && !$affectation->getEmploye()) {
                $this->addFlash('error', 'L\'employé doit être affecté en même temps que le matériel à une société.');
                return $this->redirectToRoute('affectations_create');
            }

            // Mise à jour du statut du matériel et de son lieu d'affectation
            $materiel = $affectation->getMateriel();
            if ($materiel) {
                $materiel->setStatut(1); 
                $materiel->setLieuAffactation($form->get('lieuAffectation')->getData());
                $entityManager->persist($materiel);
            }

            // Enregistrer l'affectation dans la base de données
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
        if ($this->isCsrfTokenValid('delete' . $affectation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($affectation);
            $entityManager->flush();

            $this->addFlash('success', 'Affectation supprimée avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('affectations_index');
    }
    }