<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Entity\Materiel;
use App\Form\MaterielType;
use App\Repository\MaterielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/materiels')]
class MaterielController extends AbstractController
{
    #[Route('/', name: 'materiels_index', methods: ['GET'])]
    public function index(MaterielRepository $materielRepository): Response
    {
        $materiels = $materielRepository->findAll();
        return $this->render('materiels/index.html.twig', [
            'materiels' => $materiels,
        ]);
    }

    #[Route('/new', name: 'materiels_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $materiel = new Materiel();
        $materiel->setStatut(0); // Définir le statut par défaut à 0 (Non Affecté)
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($materiel);
            $entityManager->flush();

            return $this->redirectToRoute('materiels_index', [], Response::HTTP_SEE_OTHER);
        }

            // Récupération des ID des marques de véhicules
            $marquesVehicules = $entityManager->getRepository(Marque::class)
                ->createQueryBuilder('m')
                ->select('m.id')
                ->where('m.estVehicule = true')
                ->getQuery()
                ->getResult();
            
            // Conversion en tableau simple
            $marquesVehicules = array_map(fn($marque) => $marque['id'], $marquesVehicules);

        return $this->render('materiels/create.html.twig', [
            'materiel' => $materiel,
            'form' => $form->createView(),
            'marquesVehicules' => $marquesVehicules,
        ]);
    }

    #[Route('/{id}', name: 'materiels_show', methods: ['GET'])]
    public function show(Materiel $materiel): Response
    {
        return $this->render('materiels/show.html.twig', [
            'materiel' => $materiel,
        ]);
    }

    #[Route('/{id}/edit', name: 'materiels_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Materiel $materiel, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('materiels_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('materiels/edit.html.twig', [
            'materiel' => $materiel,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'materiels_delete', methods: ['POST'])]
    public function delete(Request $request, Materiel $materiel, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$materiel->getId(), $request->request->get('_token'))) {
            $entityManager->remove($materiel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('materiels_index', [], Response::HTTP_SEE_OTHER);
    }
}