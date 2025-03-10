<?php

namespace App\Controller;

use App\Entity\TypeMateriel;
use App\Form\TypeMaterielType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TypeMaterielRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/types_materiels')]
class TypeMaterielController extends AbstractController
{
    #[Route('/', name: 'types_materiels_index', methods: ['GET'])]
    public function index(TypeMaterielRepository $typeMaterielRepository): Response
    {
        $typesMateriels = $typeMaterielRepository->findAll();
        
        return $this->render('types_materiels/index.html.twig', [
            'typesMateriels' => $typesMateriels,
        ]);
    }

    #[Route('/new', name: 'types_materiels_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $typeMateriel = new TypeMateriel();
        $form = $this->createForm(TypeMaterielType::class, $typeMateriel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($typeMateriel);
            $em->flush();

            return $this->redirectToRoute('types_materiels_index');
        }

        return $this->render('types_materiels/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'types_materiels_show', methods: ['GET'])]
    public function show(TypeMateriel $typeMateriel): Response
    {
        return $this->render('types_materiels/show.html.twig', [
            'typeMateriel' => $typeMateriel,
        ]);
    }

    #[Route('/{id}/edit', name: 'types_materiels_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeMateriel $typeMateriel, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TypeMaterielType::class, $typeMateriel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('types_materiels_index');
        }

        return $this->render('types_materiels/edit.html.twig', [
            'form' => $form->createView(),
            'typeMateriel' => $typeMateriel,
        ]);
    }

    #[Route('/{id}/delete', name: 'types_materiels_delete', methods: ['POST'])]
    public function delete(Request $request, TypeMateriel $typeMateriel, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $typeMateriel->getId(), $request->request->get('_token'))) {
            $em->remove($typeMateriel);
            $em->flush();
        }

        return $this->redirectToRoute('types_materiels_index');
    }
}