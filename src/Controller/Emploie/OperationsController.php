<?php

declare(strict_types=1);

namespace App\Controller\Emploie;

use App\Entity\Emploie\OperationEmploie;
use App\Enum\OperationsStatut;
use App\Form\Emploie\DemandeModificationEmploieType;
use App\Repository\Emploie\OperationEmploieRepository;
use App\Repository\Emploie\RessourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OperationsController extends AbstractController
{
    #[Route('/operations/index', name: 'app_emploie_operations_index', methods: ['GET'])]
    public function index(RessourceRepository $ressourceRepository,OperationEmploieRepository $operationEmploieRepository): Response
    {
        return $this->render('emploie/operations/index.html.twig',[
            'ressources' => $ressourceRepository->findAll(),
            'operation_emploies' => $operationEmploieRepository->findAll(),
            'sommes'=>$operationEmploieRepository->getTotalRetenue(),
        ]);
    }

    #[Route('/operations/autorisation_modification/{id}', name: 'app_emploie_operations_autorisation_modification',
        methods: ['GET','POST'])]
    public function autorisatioModification(Request $request,OperationEmploie $operationEmploie,OperationEmploieRepository
    $operationEmploieRepository,EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(DemandeModificationEmploieType::class, $operationEmploie);
        $form->handleRequest($request);

//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->persist($operationEmploie);
//            $operationEmploie->setAutorisation(OperationsStatut::DEMANDE_MODIFICATION);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_emploie_caisse_index', [], Response::HTTP_SEE_OTHER);
//        }
//        return $this->render('emploie/operations/autorisation_modification.html.twig',[
//            'operation_emploies' => $operationEmploieRepository->findAll(),
//            'form' => $form,
//        ]);
        return $this->handleEmploieForm($request, $entityManager, $operationEmploie, false);
    }
    #[Route('/{id}/modal-form', name: 'app_emploie_modification_modal_edit_form', methods: ['GET'])]
    public function modalEditForm(OperationEmploie $emploie): Response
    {
        return $this->renderModalForm($emploie, true);
    }
    /**
     * Fonction privée pour gérer le formulaire d'emploi (ajout/modification)
     */
    private function handleEmploieForm(Request $request, EntityManagerInterface $entityManager, OperationEmploie $emploie, bool $isEdit): Response
    {
        $form = $this->createForm(DemandeModificationEmploieType::class, $emploie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$isEdit) {
                $emploie->setAutorisation(OperationsStatut::DEMANDE_MODIFICATION);
                $entityManager->persist($emploie);
            }
            $entityManager->flush();

            // Si c'est une requête AJAX, retourner du JSON
            if ($request->isXmlHttpRequest()) {
                return $this->createSuccessResponse($emploie, $isEdit);
            }

            return $this->redirectToRoute('app_emploie_operations_index', [], Response::HTTP_SEE_OTHER);
        }

        // Si c'est une requête AJAX et qu'il y a des erreurs
        if ($request->isXmlHttpRequest()) {
            return $this->createErrorResponse($form, $emploie, $isEdit);
        }

        // Rendu classique (non-AJAX)
        $template = $isEdit ? 'emploie/operations/autorisation_modification.html.twig' : 'emploie/operations/autorisation_modification.html.twig';
        return $this->render($template, [
            'emploie' => $emploie,
            'form' => $form,
        ]);
    }

    /**
     * Fonction privée pour rendre le formulaire modal
     */
    private function renderModalForm(OperationEmploie $emploie, bool $isEdit): Response
    {
        $form = $this->createForm(DemandeModificationEmploieType::class, $emploie);

        return $this->render('emploie/emploie/_autorisation_form_modal.html.twig', [
            'form' => $form->createView(),
            'emploie' => $emploie,
            'is_edit' => $isEdit
        ]);
    }

    /**
     * Fonction privée pour créer une réponse JSON de succès
     */
    private function createSuccessResponse(OperationEmploie $emploie, bool $isEdit): JsonResponse
    {
        $message = $isEdit ? 'Emploi modifié avec succès' : 'Emploi ajouté avec succès';

        return new JsonResponse([
            'success' => true,
            'message' => $message,
            'emploie' => [
                'id' => $emploie->getId(),
                'libelle' => $emploie->getReferenceManuel()
            ]
        ]);
    }

    /**
     * Fonction privée pour créer une réponse JSON d'erreur
     */
    private function createErrorResponse($form, OperationEmploie $emploie, bool $isEdit): JsonResponse
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        $templateVars = ['form' => $form->createView()];
        if ($isEdit) {
            $templateVars['emploie'] = $emploie;
            $templateVars['is_edit'] = true;
        }

        return new JsonResponse([
            'success' => false,
            'errors' => $errors,
            'form' => $this->renderView('emploie/emploie/_autorisation_form_modal.html.twig', $templateVars)
        ]);
    }

    public function modification(Request $request, OperationEmploie $operationEmploie,EntityManagerInterface $entityManager): Response
    {
        return $this->renderModalForm(new OperationEmploie(), false);
    }
}
