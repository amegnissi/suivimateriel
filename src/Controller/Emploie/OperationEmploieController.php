<?php

namespace App\Controller\Emploie;

use App\Entity\Emploie\OperationEmploie;
use App\Entity\Emploie\Periode;
use App\Enum\OperationsStatut;
use App\Form\Emploie\OperationEmploieType;
use App\Repository\Emploie\ExerciceRepository;
use App\Repository\Emploie\MoisRepository;
use App\Repository\Emploie\OperationEmploieRepository;
use App\Repository\Emploie\PeriodeRepository;
use App\Service\UniqueIdentifierGenerator;
use App\Traits\InsertionReferenceTrait;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/emploie/operation/emploie')]
final class OperationEmploieController extends AbstractController
{
    use InsertionReferenceTrait;
    #[Route(name: 'app_emploie_operation_emploie_index', methods: ['GET'])]
    public function index(OperationEmploieRepository $operationEmploieRepository,Request $request): Response
    {
        $session = $request->getSession();
        $periode = $session->get('selected_periode_id');

        return $this->render('emploie/operation_emploie/index.html.twig', [
            'operation_emploies' => $operationEmploieRepository->findAll(),
            'total_montant_apayer' => $operationEmploieRepository->getTotalMontantAPayer( $periode) ?? 0,
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UniqueIdentifierGenerator $uniqueIdentifierGenerator
     * @return Response
     */
    #[Route('/new', name: 'app_emploie_operation_emploie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UniqueIdentifierGenerator $uniqueIdentifierGenerator,PeriodeRepository $periodeRepository,MoisRepository $moisRepository,ExerciceRepository $exerciceRepository): Response
    {
        $session = $request->getSession();
        $periode = $session->get('selected_periode_id');
        $p =  $periodeRepository->find($periode);
        $operationEmploie = new OperationEmploie();
//        dd($p->getExercice());
//        $date = new DateTime($p->getExercice()->getAnnee()-$p->getMois()->getLibelle());
     //   $date_mois = DateTime::createFromFormat('F', $p->getMois()->getLibelle());
      //  dd( $p->getMois()->getLibelle(),$date_mois, $date_mois->format('m'));
        $operationEmploie->setMois((int) $p->getMois()->getId());
        $operationEmploie->setAnnee((int) $p->getExercice()->getAnnee() );
        $operationEmploie->setDateOperation(new \DateTime());

        $form = $this->createForm(OperationEmploieType::class, $operationEmploie);
        $identifier = $uniqueIdentifierGenerator->generateUniqueIdentifier(OperationEmploie::class, 'referenceSysteme', 'EMP');

        $form->get('referenceManuel')->setData(modelData: $identifier);
//        Exercice 2025 mois de Septembre
        $periodeLabel = 'Exercice '.$p->getExercice()->getAnnee().' du mois '.$p->getMois()->getLibelle() .' ' ;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $operationEmploie->setPeriode( $p);
            $this->insertion($identifier,$operationEmploie,$form);
            $entityManager->persist($operationEmploie);
            $entityManager->flush();

            return $this->redirectToRoute('app_emploie_operations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('emploie/operation_emploie/new.html.twig', [
            'operation_emploie' => $operationEmploie,
            'form' => $form,
            'periodelibelle'=>$periodeLabel
        ]);
    }

    #[Route('/cloture-periode/{id}', name: 'app_emploie_operation_emploie_cloture', methods: ['GET', 'POST'])]
    public function cloture(Request $request, Periode $periode,EntityManagerInterface $entityManager, PeriodeRepository $periodeRepository,MoisRepository $moisRepository,ExerciceRepository $exerciceRepository): Response
    {
       $periode->setIsCloture(true);

             $entityManager->persist( $periode);
            $entityManager->flush();
            return $this->redirectToRoute('app_emploie_operations_cloture_index', [], Response::HTTP_SEE_OTHER);


    }


    #[Route('/{id}', name: 'app_emploie_operation_emploie_show', methods: ['GET'])]
    public function show(OperationEmploie $operationEmploie): Response
    {
        return $this->render('emploie/operation_emploie/show.html.twig', [
            'operation_emploie' => $operationEmploie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_emploie_operation_emploie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OperationEmploie $operationEmploie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OperationEmploieType::class, $operationEmploie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on recupere le role de l'utilisateur
            $role = $this->getUser()->getRoles()[0];
//            dd($role);
            if($role == "ROLE_SECRETAIRE"){
                $operationEmploie->setAutorisation(OperationsStatut::MODIFICATION_SECRETAIRE);

            } elseif ($role == "ROLE_SUPER_ADMIN") {
                $operationEmploie->setAutorisation(OperationsStatut::MODIFICATION_DG);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_emploie_operations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('emploie/operation_emploie/edit.html.twig', [
            'operation_emploie' => $operationEmploie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emploie_operation_emploie_delete', methods: ['POST'])]
    public function delete(Request $request, OperationEmploie $operationEmploie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$operationEmploie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($operationEmploie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_emploie_operation_emploie_index', [], Response::HTTP_SEE_OTHER);
    }
}
