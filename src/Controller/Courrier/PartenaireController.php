<?php

namespace App\Controller\Courrier;

use App\Entity\Courrier\Partenaire;
use App\Form\Courrier\PartenaireType;
use App\Repository\courrier\CourrierRepository;
use App\Repository\courrier\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/courriers/partenaire')]
final class PartenaireController extends AbstractController
{
    #[Route(name: 'app_courrier_partenaire_index', methods: ['GET'])]
    public function index(PartenaireRepository $partenaireRepository): Response
    {
        return $this->render('courrier/partenaire/index.html.twig', [
            'partenaires' => $partenaireRepository->findAll(),
            
        ]);
    }

    #[Route('/new', name: 'app_courrier_partenaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $partenaire = new Partenaire();
        $form = $this->createForm(PartenaireType::class, $partenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($partenaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_courrier_partenaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('courrier/partenaire/new.html.twig', [
            'partenaire' => $partenaire,
            'form' => $form,
            
        ]);
    }

    #[Route('/{id}', name: 'app_courrier_partenaire_show', methods: ['GET'])]
    public function show(Partenaire $partenaire, CourrierRepository $courrierRepository): Response
    {
        return $this->render('courrier/partenaire/show.html.twig', [
            'partenaire' => $partenaire,
            'courriers' => $courrierRepository->findBy([
                'partenaire'=>$partenaire,
                
            ])
        ]);
    }

    #[Route('/{id}/edit', name: 'app_courrier_partenaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Partenaire $partenaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PartenaireType::class, $partenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_courrier_partenaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('courrier/partenaire/edit.html.twig', [
            'partenaire' => $partenaire,
            'form' => $form,
            
        ]);
    }

    #[Route('/{id}', name: 'app_courrier_partenaire_delete', methods: ['POST'])]
    public function delete(Request $request, Partenaire $partenaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$partenaire->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($partenaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_courrier_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }
}