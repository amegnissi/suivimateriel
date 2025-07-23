<?php

namespace App\Controller\Courrier;

use App\Entity\Courrier\NatureCourrier;
use App\Form\Courrier\NatureCourrierType;
use App\Repository\Courrier\NatureCourrierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/courriers/nature')]
final class NatureCourrierController extends AbstractController
{
    #[Route(name: 'app_courrier_nature_index', methods: ['GET'])]
    public function index(NatureCourrierRepository $natureCourrierRepository): Response
    {
        return $this->render('courrier/nature_courrier/index.html.twig', [
            'nature_courriers' => $natureCourrierRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_courrier_nature_courrier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $natureCourrier = new NatureCourrier();
        $form = $this->createForm(NatureCourrierType::class, $natureCourrier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($natureCourrier);
            $entityManager->flush();

            return $this->redirectToRoute('app_courrier_nature_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('courrier/nature_courrier/new.html.twig', [
            'nature_courrier' => $natureCourrier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_courrier_nature_courrier_show', methods: ['GET'])]
    public function show(NatureCourrier $natureCourrier): Response
    {
        return $this->render('courrier/nature_courrier/show.html.twig', [
            'nature_courrier' => $natureCourrier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_courrier_nature_courrier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, NatureCourrier $natureCourrier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NatureCourrierType::class, $natureCourrier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_courrier_nature_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('courrier/nature_courrier/edit.html.twig', [
            'nature_courrier' => $natureCourrier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_courrier_nature_courrier_delete', methods: ['POST'])]
    public function delete(Request $request, NatureCourrier $natureCourrier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$natureCourrier->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($natureCourrier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_courrier_nature_courrier_index', [], Response::HTTP_SEE_OTHER);
    }
}
