<?php

namespace App\Controller\Emploie;

use App\Entity\Emploie\OperationEmploie;
use App\Form\Emploie\OperationEmploieType;
use App\Repository\Emploie\OperationEmploieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/emploie/operation/emploie')]
final class OperationEmploieController extends AbstractController
{
    #[Route(name: 'app_emploie_operation_emploie_index', methods: ['GET'])]
    public function index(OperationEmploieRepository $operationEmploieRepository): Response
    {
        return $this->render('emploie/operation_emploie/index.html.twig', [
            'operation_emploies' => $operationEmploieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_emploie_operation_emploie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $operationEmploie = new OperationEmploie();
        $operationEmploie->setMois((int) date('n'));
        $operationEmploie->setAnnee((int) date('Y'));

        $form = $this->createForm(OperationEmploieType::class, $operationEmploie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($operationEmploie);
            $entityManager->flush();

            return $this->redirectToRoute('app_emploie_operations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('emploie/operation_emploie/new.html.twig', [
            'operation_emploie' => $operationEmploie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emploie_operation_emploie_show', methods: ['GET'])]
    public function show(OperationEmploie $operationEmploie): Response
    {
        return $this->render('emploie/operation_emploie/show.html.twig', [
            'operation_emploie' => $operationEmploie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_emploie_operation_emploie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OperationEmploie $operationEmploie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OperationEmploieType::class, $operationEmploie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_emploie_operation_emploie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('emploie/operation_emploie/edit.html.twig', [
            'operation_emploie' => $operationEmploie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emploie_operation_emploie_delete', methods: ['POST'])]
    public function delete(Request $request, OperationEmploie $operationEmploie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$operationEmploie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($operationEmploie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_emploie_operation_emploie_index', [], Response::HTTP_SEE_OTHER);
    }
}
