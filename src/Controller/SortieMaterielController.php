<?php

namespace App\Controller;

use App\Entity\Materiel;
use App\Entity\Entreprise;
use App\Entity\SortieMateriel;
use App\Form\SortieMaterielType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\SortieMaterielRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/sortie-materiels')]
class SortieMaterielController extends BaseController
{
    #[Route('/', name: 'sortie_materiels_index', methods: ['GET'])]
    public function index(Request $request, SortieMaterielRepository $repository, PaginatorInterface $paginator, EntityManagerInterface $em): Response
    {
        if ($redirect = $this->checkEntreprise($em)) {
            return $redirect;
        }

        $query = $repository->createQueryBuilder('s')
            ->orderBy('s.dateSortie', 'DESC')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('sortie_materiel/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/sortie/new', name: 'sortie_materiel_create', methods: ['GET', 'POST'])]
    public function createSortie(Request $request, EntityManagerInterface $em): Response
    {
        if ($redirect = $this->checkEntreprise($em)) {
            return $redirect;
        }
    
        $sortie = new SortieMateriel();
        $form = $this->createForm(SortieMaterielType::class, $sortie);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $materiel = $sortie->getMateriel();
    
            // Vérifier si le matériel est déjà sorti
            if ($materiel && $materiel->getEstSorti()) {
                $this->addFlash('warning', 'Ce matériel est déjà en sortie.');
                return $this->redirectToRoute('sortie_materiel_create');
            }
    
            $sortie->setDateSortie(new \DateTime());
    
            // Marquer le matériel comme sorti
            $materiel->setEstSorti(true);
            $em->persist($materiel);
    
            // Enregistrer la sortie
            $em->persist($sortie);
            $em->flush();
    
            $this->addFlash('success', 'Sortie de matériel enregistrée avec succès.');
            return $this->redirectToRoute('sortie_materiels_index');
        }
    
        return $this->render('sortie_materiel/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/retour/{id}', name: 'sorties_retour', methods: ['GET', 'POST'])]
    public function retour(SortieMateriel $sortie, EntityManagerInterface $em): Response
    {
        if ($sortie->getDateRetour() !== null) {
            $this->addFlash('warning', 'Ce matériel a déjà été retourné.');
            return $this->redirectToRoute('sortie_materiels_historique');
        }

        $sortie->setDateRetour(new \DateTime());
        $sortie->getMateriel()->setEstSorti(false); // Le matériel est revenu

        $em->flush();

        $this->addFlash('success', 'Matériel retourné avec succès.');
        return $this->redirectToRoute('sorties_historique');
    }

    #[Route('/historique', name: 'sorties_historique', methods: ['GET'])]
    public function historique(SortieMaterielRepository $repository, EntityManagerInterface $em): Response
    {
        if ($redirect = $this->checkEntreprise($em)) {
            return $redirect;
        }

        $historique = $repository->findBy([], ['dateSortie' => 'DESC']);

        return $this->render('sortie_materiel/historique.html.twig', [
            'sorties' => $historique,
        ]);
    }
}
