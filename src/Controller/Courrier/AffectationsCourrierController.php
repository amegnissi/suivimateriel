<?php

declare(strict_types=1);

namespace App\Controller\Courrier;

use App\Entity\Courrier\AffectationCourrier;
use App\Entity\Courrier\Courrier;
use App\Form\Courrier\AffectationsCourrierType;
use App\Repository\Courrier\AffectationCourrierRepository;
use App\Repository\Courrier\StatutRepository;
use App\Repository\EmployeRepository;
use App\Service\FileUploader;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/affectation-courrier')]
class AffectationsCourrierController extends AbstractController
{
    #[Route('/agent/{id}', name: 'app_affectation_courrier_agent')]
    public function index(Request                $request, Courrier $courrier, EmployeRepository $employeRepository,
                          EntityManagerInterface $entityManager, StatutRepository $statutRepository): Response

    {
        $affectationCourrier = new AffectationCourrier();
        $form = $this->createForm(AffectationsCourrierType::class, $affectationCourrier);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $idAgentsSelectionnes = $request->get('idsAgentsSel');
            $statutAffectee = $statutRepository->findOneBy(array('code' => 'AFFECTEE'));
            foreach ($idAgentsSelectionnes as $idAgent) {
                $affectationCourrier = new AffectationCourrier();
                $affectationCourrier->setDestinataire($employeRepository->find($idAgent));
                $affectationCourrier->setExpediteur($employeRepository->find($this->getUser()->getId()));
                $affectationCourrier->setCourrier($courrier);
                $affectationCourrier->setTraite(false);
                $affectationCourrier->setStatut($statutAffectee);
                $affectationCourrier->setDateAffectation(new DateTime());

                $entityManager->persist($affectationCourrier);
                $entityManager->flush();
            }
            $this->addFlash('success', 'Affectation effectuer avec success');
            return $this->redirectToRoute('app_affectation_courrier_affectation', [
                'id' => $courrier->getId(),
            ]);
        }

        return $this->render('courrier/affectation/affectation_courrier.html.twig', [
            'courrier' => $courrier,
//            'affectation' => $affectation,
            'form' => $form,
            'agents' => $employeRepository->findAll(),
        ]);
    }
    #[Route('/agent/show/courrier/{id}', name: 'app_courrier_affectation_show', methods: ['GET'])]
    public function show(Courrier $courrier,AffectationCourrierRepository $affectationCourrierRepository): Response
    {
        $affectation = $affectationCourrierRepository->findOneBy(['courrier' => $courrier,'destinataire' =>
            $this->getUser()->getEmploye()]);
        return $this->render('courrier/showDestinataire.html.twig', [
            'courrier' => $courrier,
            'affectation' => $affectation

        ]);
    }
    #[Route('/liste-affectation/{id}', name: 'app_affectation_courrier_affectation')]
    public function listeAffectationParCourrier(Courrier $courrier): Response
    {
        $affectations = $courrier->getAffectations();
//        dd($affectations);
        return $this->render('courrier/affectation/liste_affectation_par_courrier.html.twig', [
            'affectations' => $affectations,
            'courrier' => $courrier
        ]);
    }

    #[Route('/mes-affectations', name: 'app_affectation_courrier_affectation_agent')]
    public function mesAffectations(AffectationCourrierRepository $affectationCourrierRepository): Response
    {
        $mesAffectations = $affectationCourrierRepository->findBy(['destinataire' => $this->getUser()->getEmploye()]);
//        dd($mesAffectations);
        return $this->render('courrier/affectation/liste_affectation_utilisateur.html.twig', [
            'affectations' => $mesAffectations,
        ]);
    }

    #[Route('/traiter/{id}', name: 'app_courrier_affectation_traiter', methods: ['GET', 'POST'])]
    public function traiterAffection(Request $request, AffectationCourrier $affectationCourrier, AffectationCourrierRepository
    $affectationCourrierRepository, FileUploader $fileUploader,StatutRepository $statutRepository,
                                     EntityManagerInterface $entityManager): Response{

        $statutTraitee = $statutRepository->findOneBy(array('code' => 'TRAITEE'));
        $form = $this->createForm(AffectationsCourrierType::class, $affectationCourrier);
        $form->handleRequest($request);

        if ($form->isSubmitted()  && $form->isValid()) {

            $affectationCourrier->setDateTraitement(new DateTime());
            $affectationCourrier->setStatut($statutTraitee);
            $affectationCourrier->setRecu(true);
            $affectationCourrier->setTraite(true);
            $affectationCourrier->setObservationsTraitement($affectationCourrier->getObservation());
            if($form->get('fichier')){
                $filename = $affectationCourrier->getCourrier()->getReferenceInterne()."_".$this->getUser()
                        ->getEmploye()->getFullname();
//                $fileUploader->remove($filename,'courriers');
                $fichier = $form->get('fichier')->getData();
                $fichier = $fileUploader->upload($fichier,$filename,'courriers');
                $affectationCourrier->setFichierTraitement($fichier);
            }

            $entityManager->persist($affectationCourrier);
            $entityManager->flush();
            $this->addFlash('success', 'Traitement effectuer avec success');
            return $this->redirectToRoute('app_affectation_courrier_affectation_agent', [

            ]);

        }
        return $this->render('courrier/affectation/traitement_courrier.html.twig', array(
            'affectation' => $affectationCourrier,
            'form' => $form->createView(),
        ));

    }
}
