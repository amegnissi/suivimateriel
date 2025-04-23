<?php

namespace App\Controller;

use App\Entity\TypeAssurance;
use App\Form\TypeAssuranceType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TypeAssuranceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/type-assurance')]
class TypeAssuranceController extends BaseController
{
    #[Route('/', name: 'type_assurance_index', methods: ['GET'])]
    public function index(Request $request, TypeAssuranceRepository $typeAssuranceRepository, PaginatorInterface $paginator): Response
    {
        $query = $typeAssuranceRepository->createQueryBuilder('t')->orderBy('t.libelle', 'ASC');
        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), 10);

        return $this->render('type_assurance/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'type_assurance_create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $typeAssurance = new TypeAssurance();
        $form = $this->createForm(TypeAssuranceType::class, $typeAssurance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($typeAssurance);
            $em->flush();

            $this->addFlash('success', 'Type d\'opération ajouté avec succès.');
            return $this->redirectToRoute('type_assurance_index');
        }

        return $this->render('type_assurance/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'type_assurance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeAssurance $typeAssurance, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TypeAssuranceType::class, $typeAssurance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Type d\'opération modifié avec succès.');
            return $this->redirectToRoute('type_assurance_index');
        }

        return $this->render('type_assurance/edit.html.twig', [
            'typeAssurance' => $typeAssurance,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'type_assurance_delete', methods: ['POST'])]
    public function delete(Request $request, TypeAssurance $typeAssurance, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeAssurance->getId(), $request->request->get('_token'))) {
            $em->remove($typeAssurance);
            $em->flush();
            $this->addFlash('danger', 'Type d\'opération supprimé.');
        }

        return $this->redirectToRoute('type_assurance_index');
    }
}
