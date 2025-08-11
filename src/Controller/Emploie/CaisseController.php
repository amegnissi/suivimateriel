<?php

namespace App\Controller\Emploie;

use App\Entity\Emploie\Caisse;
use App\Form\Emploie\CaisseType;
use App\Repository\Emploie\CaisseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/emploie-resources/caisse')]
final class CaisseController extends AbstractController
{
    #[Route(name: 'app_emploie_caisse_index', methods: ['GET'])]
    public function index(CaisseRepository $caisseRepository): Response
    {
        return $this->render('emploie/caisse/index.html.twig', [
            'caisses' => $caisseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_emploie_caisse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $caisse = new Caisse();
        $form = $this->createForm(CaisseType::class, $caisse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($caisse);
            $entityManager->flush();

            return $this->redirectToRoute('app_emploie_caisse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('emploie/caisse/new.html.twig', [
            'caisse' => $caisse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emploie_caisse_show', methods: ['GET'])]
    public function show(Caisse $caisse): Response
    {
        return $this->render('emploie/caisse/show.html.twig', [
            'caisse' => $caisse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_emploie_caisse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Caisse $caisse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CaisseType::class, $caisse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_emploie_caisse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('emploie/caisse/edit.html.twig', [
            'caisse' => $caisse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emploie_caisse_delete', methods: ['POST'])]
    public function delete(Request $request, Caisse $caisse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$caisse->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($caisse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_emploie_caisse_index', [], Response::HTTP_SEE_OTHER);
    }
}
