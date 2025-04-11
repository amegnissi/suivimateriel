<?php

namespace App\Controller\Agenda;

use App\Entity\TypeEvenements;
use App\Form\TypeEvenementsType;
use App\Repository\TypeEvenementsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/type/evenements')]
final class TypeEvenementsController extends AbstractController
{
    #[Route(name: 'admin_type_evenements_index', methods: ['GET'])]
    public function index(TypeEvenementsRepository $typeEvenementsRepository): Response
    {
        return $this->render('agenda/type_evenements/index.html.twig', [
            'type_evenements' => $typeEvenementsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_type_evenements_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeEvenement = new TypeEvenements();
        $form = $this->createForm(TypeEvenementsType::class, $typeEvenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeEvenement);
            $entityManager->flush();
            $this->addFlash('success', 'Le type enregistrée avec succès.');

            return $this->redirectToRoute('admin_type_evenements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('agenda/type_evenements/new.html.twig', [
            'type_evenement' => $typeEvenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_type_evenements_show', methods: ['GET'])]
    public function show(TypeEvenements $typeEvenement): Response
    {
        return $this->render('agenda/type_evenements/show.html.twig', [
            'type_evenement' => $typeEvenement,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_type_evenements_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeEvenements $typeEvenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeEvenementsType::class, $typeEvenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_type_evenements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('agenda/type_evenements/edit.html.twig', [
            'type_evenement' => $typeEvenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_evenements_delete', methods: ['POST'])]
    public function delete(Request $request, TypeEvenements $typeEvenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeEvenement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($typeEvenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_type_evenements_index', [], Response::HTTP_SEE_OTHER);
    }
}
