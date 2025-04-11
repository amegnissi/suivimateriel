<?php

namespace App\Controller;

use App\Entity\LieuAffectation;
use App\Form\LieuAffectationType;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\LieuAffectationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/lieux-affectation', name: 'lieux_affectation_')]
class LieuAffectationController extends BaseController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(Request $request, LieuAffectationRepository $repository, PaginatorInterface $paginator, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }

        $query = $repository->createQueryBuilder('l')
            ->orderBy('l.nom', 'ASC')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('lieu_affectation/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }

        $lieu = new LieuAffectation();
        $form = $this->createForm(LieuAffectationType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Si type interne, on affecte l'entreprise courante
            if ($lieu->getType() === 'interne') {
                $lieu->setEntreprise($this->getEntreprise($entityManager));
                $lieu->setSocieteService(null);
            }

            $entityManager->persist($lieu);
            $entityManager->flush();

            $this->addFlash('success', 'Lieu d\'affectation ajouté avec succès.');
            return $this->redirectToRoute('lieux_affectation_index');
        }

        return $this->render('lieu_affectation/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(LieuAffectation $lieu): Response
    {
        return $this->render('lieu_affectation/show.html.twig', [
            'lieu' => $lieu,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LieuAffectation $lieu, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }

        $form = $this->createForm(LieuAffectationType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($lieu->getType() === 'interne') {
                $lieu->setEntreprise($this->getEntreprise($entityManager));
                $lieu->setSocieteService(null);
            } elseif ($lieu->getType() === 'externe') {
                $lieu->setEntreprise(null);
            }
        
            $entityManager->persist($lieu);
            $entityManager->flush();
        
            $this->addFlash('success', 'Lieu ou service ajouté avec succès.');
            return $this->redirectToRoute('lieu_affectation_index');
        }

        return $this->render('lieu_affectation/edit.html.twig', [
            'form' => $form->createView(),
            'lieu' => $lieu,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, LieuAffectation $lieu, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }

        if ($this->isCsrfTokenValid('delete' . $lieu->getId(), $request->request->get('_token'))) {
            $entityManager->remove($lieu);
            $entityManager->flush();
            $this->addFlash('success', 'Lieu d\'affectation supprimé avec succès.');
        }

        return $this->redirectToRoute('lieu_affectation_index');
    }
}
