<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/entreprise')]
class EntrepriseController extends AbstractController
{
    #[Route('/', name: 'entreprise_index')]
    public function index(): Response
    {
        return $this->render('entreprise/index.html.twig');
    }

    #[Route('/new', name: 'entreprise_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entreprise = new Entreprise();
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion du fichier logo
            $logo = $form->get('logo')->getData();
            if ($logo) {
                $newFilename = uniqid().'.'.$logo->guessExtension();
                $logo->move($this->getParameter('uploads_directory').'/entreprise', $newFilename);
                $entreprise->setLogo($newFilename);
            }

            $entityManager->persist($entreprise);
            $entityManager->flush();

            $this->addFlash('success', 'Entreprise ajoutée avec succès.');
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('entreprise/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'entreprise_edit')]
    public function edit(Entreprise $entreprise, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // Gestion du fichier logo
            $logo = $form->get('logo')->getData();
            if ($logo) {
                $newFilename = uniqid().'.'.$logo->guessExtension();

                // Suppression de l'ancien logo s'il existe
                if ($entreprise->getLogo()) {
                    $oldLogoPath = $this->getParameter('uploads_directory').'/entreprise/'.$entreprise->getLogo();
                    if (file_exists($oldLogoPath)) {
                        unlink($oldLogoPath);
                    }
                }

                $logo->move($this->getParameter('uploads_directory').'/entreprise', $newFilename);
                $entreprise->setLogo($newFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Entreprise mise à jour avec succès.');
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('entreprise/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}