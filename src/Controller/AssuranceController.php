<?php

namespace App\Controller;

use App\Entity\Materiel;
use App\Entity\Assurance;
use App\Entity\Entreprise;
use App\Form\AssuranceType;
use App\Repository\MaterielRepository;
use App\Repository\AssuranceRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/assurances')]
class AssuranceController extends BaseController
{
    #[Route('/', name: 'assurances_index', methods: ['GET'])]
    public function index(Request $request, AssuranceRepository $assuranceRepository, PaginatorInterface $paginator, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }

        $query = $assuranceRepository->createQueryBuilder('a')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('assurances/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'assurances_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, MaterielRepository $materielRepository): Response
    {
        // Vérifier si l'entreprise existe
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }

        // Récupérer l'entité Entreprise
        $entreprise = $entityManager->getRepository(Entreprise::class)->findOneBy([]);

        // Vérifier si les délais sont définis dans l'entreprise
        if (!$entreprise || !$entreprise->getDelaiAssurance() || !$entreprise->getDelaiVisiteTechnique() || !$entreprise->getDelaiTVM()) {
            $this->addFlash('danger', 'Les délais pour l\'assurance, la visite technique ou le TVM ne sont pas définis dans l\'entreprise.');
            return $this->redirectToRoute('assurances_index');
        }

        $assurance = new Assurance();
        $form = $this->createForm(AssuranceType::class, $assurance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $materiel = $assurance->getMateriel();
            if (!$materiel) {
                $this->addFlash('danger', 'Veuillez sélectionner un véhicule.');
                return $this->redirectToRoute('assurances_create');
            }

            $entityManager->persist($assurance);
            $entityManager->flush();

            $this->addFlash('success', 'Opération enregistrée avec succès.');
            return $this->redirectToRoute('assurances_index');
        }

        return $this->render('assurances/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'assurances_show', methods: ['GET'])]
    public function show(Assurance $assurance): Response
    {
        return $this->render('assurances/show.html.twig', [
            'assurance' => $assurance,
        ]);
    }

    #[Route('/{id}/delete', name: 'assurances_delete', methods: ['POST'])]
    public function delete(Assurance $assurance, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($assurance);
        $entityManager->flush();

        return $this->redirectToRoute('assurances_index');
    }

    #[Route('/type/{type}', name: 'assurances_fin', methods: ['GET'])]
    public function indexEnd(Request $request, AssuranceRepository $assuranceRepository, PaginatorInterface $paginator,
                           EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }

        $type = $request->get('type');
        if ($type == 'assurance'){
            $query =  $assuranceRepository->findAssurancesExpirantParTypes(30,'assurance');
        }elseif ($type == 'visite-technique'){
            $query =  $assuranceRepository->findAssurancesExpirantParTypes(30,'visite_technique');

        }elseif ($type == 'TVM'){
            $query =  $assuranceRepository->findAssurancesExpirantParTypes(30,'tvm');

        }
        else {
            $query = $assuranceRepository->createQueryBuilder('a')
                ->getQuery();
        }



        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('assurances/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

}
