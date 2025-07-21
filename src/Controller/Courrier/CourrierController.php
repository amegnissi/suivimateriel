<?php

namespace App\Controller\Courrier;

use App\Entity\Courrier\Courrier;
use App\Form\Courrier\CourrierType;
use App\Repository\courrier\CourrierRepository;
use App\Repository\courrier\TypeCourrierRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/courrier')]
final class CourrierController extends AbstractController
{
    
    #[Route(name: 'app_courrier_index', methods: ['GET'])]
    public function index(CourrierRepository $courrierRepository): Response
    {
        
        return $this->render('courrier/index.html.twig', [
            'courriers' => $courrierRepository->findAll(),
            
        ]);
    }

    #[Route('/depart/new', name: 'app_courrier_depart_new', methods: ['GET', 'POST'])]
    public function newDepart(Request $request, EntityManagerInterface $entityManager, TypeCourrierRepository
                                $typeCourrierRepository, FileUploader $fileUploader): Response
    {
        $courrier = new Courrier();
        $form = $this->createForm(CourrierType::class, $courrier,['type_courrier'=>'DEPART']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('urlfichiercourrier')){
                $fichier = $form->get('urlfichiercourrier')->getData();
                $fichier = $fileUploader->upload($fichier, $form->get('referenceInterne')->getData(),'courriers');
                $courrier->setUrl($fichier);
            }
            $courrier->setTypeCourrier($typeCourrierRepository->findOneBy(array('code' => 'DEPART')));

            $entityManager->persist($courrier);
            $entityManager->flush();

            return $this->redirectToRoute('app_courrier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('courrier/new.html.twig', [
            'courrier' => $courrier,
            'form' => $form,
            'titre'=> 'Nouveau courrier départ',
            
        ]);
    }

    #[Route('/new', name: 'app_courrier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, TypeCourrierRepository
    $typeCourrierRepository, FileUploader $fileUploader): Response
    {
        $courrier = new Courrier();
        $form = $this->createForm(CourrierType::class, $courrier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('urlfichiercourrier')){
                $fichier = $form->get('urlfichiercourrier')->getData();
                $fichier = $fileUploader->upload($fichier, $form->get('referenceInterne')->getData(),'courriers');
                $courrier->setUrl($fichier);
            }
            $courrier->setTypeCourrier($typeCourrierRepository->findOneBy(array('code' => 'ARRIVEE')));

            $entityManager->persist($courrier);
            $entityManager->flush();

            return $this->redirectToRoute('app_courrier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('courrier/new.html.twig', [
            'courrier' => $courrier,
            'form' => $form,
            'titre'=> 'Nouveau courrier arrivé',
            
        ]);
    }

    #[Route('/{id}', name: 'app_courrier_show', methods: ['GET'])]
    public function show(Courrier $courrier): Response
    {
        return $this->render('courrier/show.html.twig', [
            'courrier' => $courrier,
            
        ]);
    }

    #[Route('/{id}/edit', name: 'app_courrier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Courrier $courrier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CourrierType::class, $courrier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_courrier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('courrier/edit.html.twig', [
            'courrier' => $courrier,
            'form' => $form,
            
        ]);
    }

    #[Route('/{id}', name: 'app_courrier_delete', methods: ['POST'])]
    public function delete(Request $request, Courrier $courrier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$courrier->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($courrier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_courrier_index', [], Response::HTTP_SEE_OTHER);
    }
}