<?php
//
//namespace App\Controller\Emploie;
//
//use App\Entity\Emploie\Emploie;
//use App\Form\Emploie\EmploieType;
//use App\Repository\Emploie\EmploieRepository;
//use Doctrine\ORM\EntityManagerInterface;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Attribute\Route;
//
//#[Route('/emploie')]
//final class EmploieController extends AbstractController
//{
//    #[Route(name: 'app_emploie_emploie_index', methods: ['GET'])]
//    public function index(EmploieRepository $emploieRepository): Response
//    {
//        return $this->render('emploie/emploie/index.html.twig', [
//            'emploies' => $emploieRepository->findAll(),
//        ]);
//    }
//
//    #[Route('/new', name: 'app_emploie_emploie_new', methods: ['GET', 'POST'])]
//    public function new(Request $request, EntityManagerInterface $entityManager): Response
//    {
//        $emploie = new Emploie();
//        $form = $this->createForm(EmploieType::class, $emploie);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->persist($emploie);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_emploie_emploie_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->render('emploie/emploie/new.html.twig', [
//            'emploie' => $emploie,
//            'form' => $form,
//        ]);
//    }
//
//    #[Route('/{id}', name: 'app_emploie_emploie_show', methods: ['GET'])]
//    public function show(Emploie $emploie): Response
//    {
//        return $this->render('emploie/emploie/show.html.twig', [
//            'emploie' => $emploie,
//        ]);
//    }
//
//    #[Route('/{id}/edit', name: 'app_emploie_emploie_edit', methods: ['GET', 'POST'])]
//    public function edit(Request $request, Emploie $emploie, EntityManagerInterface $entityManager): Response
//    {
//        $form = $this->createForm(EmploieType::class, $emploie);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_emploie_emploie_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->render('emploie/emploie/edit.html.twig', [
//            'emploie' => $emploie,
//            'form' => $form,
//        ]);
//    }
//
//    #[Route('/{id}', name: 'app_emploie_emploie_delete', methods: ['POST'])]
//    public function delete(Request $request, Emploie $emploie, EntityManagerInterface $entityManager): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$emploie->getId(), $request->getPayload()->getString('_token'))) {
//            $entityManager->remove($emploie);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('app_emploie_emploie_index', [], Response::HTTP_SEE_OTHER);
//    }

//}


namespace App\Controller\Emploie;

use App\Entity\Emploie\Emploie;
use App\Form\Emploie\EmploieType;
use App\Repository\Emploie\EmploieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/emploie')]
final class EmploieController extends AbstractController
{
    #[Route(name: 'app_emploie_emploie_index', methods: ['GET'])]
    public function index(EmploieRepository $emploieRepository): Response
    {
        return $this->render('emploie/emploie/index.html.twig', [
            'emploies' => $emploieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_emploie_emploie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $emploie = new Emploie();
        return $this->handleEmploieForm($request, $entityManager, $emploie, false);
    }

    #[Route('/modal-form', name: 'app_emploie_emploie_modal_form', methods: ['GET'])]
    public function modalForm(): Response
    {
        $emploie = new Emploie();
        return $this->renderModalForm($emploie, false);
    }

    #[Route('/{id}', name: 'app_emploie_emploie_show', methods: ['GET'])]
    public function show(Emploie $emploie): Response
    {
        return $this->render('emploie/emploie/show.html.twig', [
            'emploie' => $emploie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_emploie_emploie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Emploie $emploie, EntityManagerInterface $entityManager): Response
    {
        return $this->handleEmploieForm($request, $entityManager, $emploie, true);
    }

    #[Route('/{id}/modal-form', name: 'app_emploie_emploie_modal_edit_form', methods: ['GET'])]
    public function modalEditForm(Emploie $emploie): Response
    {
        return $this->renderModalForm($emploie, true);
    }

    #[Route('/{id}', name: 'app_emploie_emploie_delete', methods: ['POST'])]
    public function delete(Request $request, Emploie $emploie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $emploie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($emploie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_emploie_emploie_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Fonction privée pour gérer le formulaire d'emploi (ajout/modification)
     */
    private function handleEmploieForm(Request $request, EntityManagerInterface $entityManager, Emploie $emploie, bool $isEdit): Response
    {
        $form = $this->createForm(EmploieType::class, $emploie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$isEdit) {
                $entityManager->persist($emploie);
            }
            $entityManager->flush();

            // Si c'est une requête AJAX, retourner du JSON
            if ($request->isXmlHttpRequest()) {
                return $this->createSuccessResponse($emploie, $isEdit);
            }

            return $this->redirectToRoute('app_emploie_emploie_index', [], Response::HTTP_SEE_OTHER);
        }

        // Si c'est une requête AJAX et qu'il y a des erreurs
        if ($request->isXmlHttpRequest()) {
            return $this->createErrorResponse($form, $emploie, $isEdit);
        }

        // Rendu classique (non-AJAX)
        $template = $isEdit ? 'emploie/emploie/edit.html.twig' : 'emploie/emploie/new.html.twig';
        return $this->render($template, [
            'emploie' => $emploie,
            'form' => $form,
        ]);
    }

    /**
     * Fonction privée pour rendre le formulaire modal
     */
    private function renderModalForm(Emploie $emploie, bool $isEdit): Response
    {
        $form = $this->createForm(EmploieType::class, $emploie);

        return $this->render('emploie/emploie/_form_modal.html.twig', [
            'form' => $form->createView(),
            'emploie' => $emploie,
            'is_edit' => $isEdit
        ]);
    }

    /**
     * Fonction privée pour créer une réponse JSON de succès
     */
    private function createSuccessResponse(Emploie $emploie, bool $isEdit): JsonResponse
    {
        $message = $isEdit ? 'Emploi modifié avec succès' : 'Emploi ajouté avec succès';

        return new JsonResponse([
            'success' => true,
            'message' => $message,
            'emploie' => [
                'id' => $emploie->getId(),
                'libelle' => $emploie->getLibelle()
            ]
        ]);
    }

    /**
     * Fonction privée pour créer une réponse JSON d'erreur
     */
    private function createErrorResponse($form, Emploie $emploie, bool $isEdit): JsonResponse
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
            'form' => $this->renderView('emploie/emploie/_form_modal.html.twig', $templateVars)
        ]);
    }
}
