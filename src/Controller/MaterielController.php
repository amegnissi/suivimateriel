<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Entity\Materiel;
use App\Entity\Entreprise;
use App\Form\MaterielType;
use App\Repository\MaterielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[IsGranted('ROLE_USER')]
#[Route('/materiels')]
class MaterielController extends BaseController
{
    #[Route('/', name: 'materiels_index', methods: ['GET'])]
    public function index(Request $request, MaterielRepository $materielRepository, PaginatorInterface $paginator, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        $query = $materielRepository->createQueryBuilder('e')
            ->getQuery();

        // Paginer les résultats
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Page actuelle
            10 // Nombre d'éléments par page
        );
        
        return $this->render('materiels/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'materiels_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        $materiel = new Materiel();
        $materiel->setStatut(0); // Définir le statut par défaut à 0 (Non Affecté)
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion du fichier image
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move($this->getParameter('uploads_directory').'/materiels',$newFilename);
                $materiel->setImageFilename($newFilename);
            }

            // Associer un matériel à une entreprise
            $entreprise = $entityManager->getRepository(Entreprise::class)->find(1); // ID de l'entreprise
            if ($entreprise) {
                $materiel->setEntreprise($entreprise);
            }

            // Si le matériel est un véhicule, mettre à jour la marque
            if ($materiel->getType() === 'vehicule') {
                $marque = $materiel->getMarque();
                if ($marque) {
                    $marque->setEstVehicule(true);  // Mettre estVehicule à true
                    $entityManager->persist($marque); // Persister la marque avec la mise à jour
                }
            }

            $entityManager->persist($materiel);
            $entityManager->flush();

            $this->addFlash('success', 'Matériel ajouté avec succès.');
            return $this->redirectToRoute('materiels_index', [], Response::HTTP_SEE_OTHER);
        }

            // Récupération des ID des marques de véhicules
            $marquesVehicules = $entityManager->getRepository(Marque::class)
                ->createQueryBuilder('m')
                ->select('m.id')
                ->where('m.estVehicule = true')
                ->getQuery()
                ->getResult();
            
            // Conversion en tableau simple
            $marquesVehicules = array_map(fn($marque) => $marque['id'], $marquesVehicules);

        return $this->render('materiels/create.html.twig', [
            'materiel' => $materiel,
            'form' => $form->createView(),
            'marquesVehicules' => $marquesVehicules,
        ]);
    }

    #[Route('/{id}', name: 'materiels_show', methods: ['GET'])]
    public function show(Materiel $materiel): Response
    {
        return $this->render('materiels/show.html.twig', [
            'materiel' => $materiel,
        ]);
    }

    #[Route('/{id}/edit', name: 'materiels_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Materiel $materiel, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Matériel mis à jour avec succès.');
            return $this->redirectToRoute('materiels_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('materiels/edit.html.twig', [
            'materiel' => $materiel,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'materiels_delete', methods: ['POST'])]
    public function delete(Request $request, Materiel $materiel, EntityManagerInterface $entityManager): Response
    {
        if ($redirect = $this->checkEntreprise($entityManager)) {
            return $redirect;
        }
        
        if ($this->isCsrfTokenValid('delete'.$materiel->getId(), $request->request->get('_token'))) {
            $entityManager->remove($materiel);
            $entityManager->flush();
            $this->addFlash('success', 'Matériel supprimé avec succès.');
        }

        return $this->redirectToRoute('materiels_index', [], Response::HTTP_SEE_OTHER);
    }
}