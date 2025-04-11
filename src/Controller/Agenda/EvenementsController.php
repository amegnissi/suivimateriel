<?php

namespace App\Controller\Agenda;

use App\Entity\Evenements;
use App\Entity\Reunion;
use App\Form\EvenementsType;
use App\Repository\EvenementsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/evenements')]
final class EvenementsController extends AbstractController
{
    #[Route(name: 'admin_evenements_index', methods: ['GET'])]
    public function index(EvenementsRepository $evenementsRepository): Response
    {
        return $this->render('agenda/evenements/index.html.twig', [
            'evenements' => $evenementsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_evenements_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenements();
        $form = $this->createForm(EvenementsType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

//            dd($form->get('contact')->getData());
            $contacts = $form->get('contact')->getData();
            foreach ( $contacts as  $contact) {
                $reuniom = new Reunion();
                $reuniom
                    ->setParticipant($contact)
                    ->setEvents($evenement)
                    ->setEstArrive(false)
                ;
//                dd($reuniom);
                $entityManager->persist($reuniom);

            }
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('admin_evenements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('agenda/evenements/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenements_show', methods: ['GET'])]
    public function show(Evenements $evenement): Response
    {
        return $this->render('agenda/evenements/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evenements_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenements $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementsType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_evenements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('agenda/evenements/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenements_delete', methods: ['POST'])]
    public function delete(Request $request, Evenements $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenements_index', [], Response::HTTP_SEE_OTHER);
    }




    public function handleEditForm(Request $request, $entity, $form,EntityManagerInterface $entityManager)
    {

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //$entity = $form->getData();
            $entityManager->persist($entity);
            $entityManager->flush();

            return true;
        }

        return false;
    }

    #[Route('/popup-edit/{id}/create-form', name: 'popup_event_edit', options: ["expose" => true], methods: ['POST','GET'])]
    public function ajaxEditFormAction(Evenements $evenement)
    {
        $editForm = $this->createForm(EvenementsType::class, $evenement);

        return $this->render('agenda/evenements/popup_event_edit.html.twig', array(
            'bonsplans' => $evenement,
            'isModal' => true,
            'single' => true,
            'form' =>  $editForm,
        ));
    }

    #[Route('/popupedit/{id}/edit', name: 'popup_evenements_edit', options: ["expose" => true], methods: ['GET', 'POST'])]
    public function popupEdit(Request $request, Evenements $evenement, EntityManagerInterface $entityManager): Response
    {
        // $form = $this->createForm(RestaurantsType::class, $restaurant);
        $form = $this->createForm(EvenementsType::class, $evenement);
        if ($this->handleEditForm($request,$evenement ,$form,$entityManager)) {
            if ($request->isXmlHttpRequest()) {
                $this->addFlash('success', "L'événement  a été modifier avec succès ");

                return new JsonResponse(array(
                    'success' => true,
                ));
            } else {
                //                return $this->redirectToRoute('app_autoecole_show', array('id' => $autoEcole->getId()));
                return $this->redirectToRoute('admin_categories_index');
            }
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'success' => false,
                'errors' => $form->getErrors(true),

            ]);
        }
        else {
            return $this->render('agenda/evenements/edit.html.twig', [
                'evenement' => $evenement,
                'form' => $form,
            ]);
        }

    }
    #[Route('/popup/new', name: 'popup_evenements_new', options: ["expose" => true], methods: ['GET', 'POST'])]
    public function popupAdd(Request $request, EntityManagerInterface $entityManager): Response {
        $evenement = new Evenements();
        $form = $this->createForm(EvenementsType::class, $evenement);
        if ($this->handleEditForm($request,$evenement ,$form,$entityManager)) {
            if ($request->isXmlHttpRequest()) {
                $this->addFlash('success', "L'événement  a été modifier avec succès ");

                return new JsonResponse(array(
                    'success' => true,
                ));
            } else {
                //                return $this->redirectToRoute('app_autoecole_show', array('id' => $autoEcole->getId()));
                return $this->redirectToRoute('admin_categories_index');
            }
        }

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse([
                'success' => false,
                'errors' => $form->getErrors(true),

            ]);
        }
        else {
            return $this->render('agenda/evenements/edit.html.twig', [
                'evenement' => $evenement,
                'form' => $form,
            ]);
        }
    }
}
