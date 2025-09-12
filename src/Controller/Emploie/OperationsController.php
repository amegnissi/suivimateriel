<?php

declare(strict_types=1);

namespace App\Controller\Emploie;

use App\Entity\Emploie\OperationEmploie;
use App\Enum\OperationsStatut;
use App\Form\Emploie\DemandeModificationEmploieType;
use App\Repository\Emploie\ExerciceRepository;
use App\Repository\Emploie\MoisRepository;
use App\Repository\Emploie\OperationEmploieRepository;
use App\Repository\Emploie\PeriodeRepository;
use App\Repository\Emploie\RessourceRepository;
use App\Service\ExportService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OperationsController extends AbstractController
{
    #[Route('/operations/index', name: 'app_emploie_operations_index', methods: ['GET','POST'])]
    public function index(Request $request,RessourceRepository $ressourceRepository,OperationEmploieRepository
    $operationEmploieRepository,MoisRepository $moisRepository, ExerciceRepository $exerciceRepository,PeriodeRepository $periodeRepository): Response
    {
        $session = $request->getSession();
        $periode = $session->get('selected_periode_id');
        $moisChoices = [
            'Janvier' => 1, 'Février' => 2, 'Mars' => 3, 'Avril' => 4,
            'Mai' => 5, 'Juin' => 6, 'Juillet' => 7, 'Août' => 8,
            'Septembre' => 9, 'Octobre' => 10, 'Novembre' => 11, 'Décembre' => 12,
        ];
        $currentYear = (int) date('Y');
        $anneeChoices = array_combine(
            range(2020, $currentYear + 10),
            range(2020, $currentYear + 10)
        );
        $form = $this->createFormBuilder(null, [

        ])
            ->add('mois', ChoiceType::class, [
                'choices' => $moisChoices,
                'label' => 'Mois',
                'placeholder' => 'Choisissez un mois',
            ])
            ->add('annee', ChoiceType::class, [
                'choices' => $anneeChoices,
                'label' => 'Année',
                'placeholder' => 'Choisissez une année',
            ])
            ->getForm();

            $m = $moisRepository->findOneBy(['libelle'=>'Septembre']);
            $y = $exerciceRepository->findOneBy(['annee'=> $currentYear]);
            $period = $periodeRepository->find($periode );
//            $periode = $periodeRepository->findOneBy(['exercice'=> $y,'mois'=>  $m]);
        $form->get('mois')->setData((int) date('n'));
        $form->get('annee')->setData((int) date('Y'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mois = $form->get('mois')->getData();
            $annee = $form->get('annee')->getData();

//            dd( $mois,$annee );
            return $this->render('emploie/operations/index.html.twig',[
                'ressources' => $ressourceRepository->getRessourcePeriode($periode,$mois,$annee),
                'operation_emploies' => $operationEmploieRepository->getOperationEmploiePeriode($periode,$mois,$annee),
                'sommes'=>$operationEmploieRepository->getTotalRetenue($periode,$mois,$annee),
                'form' => $form->createView(),
                'mois'=>$mois,
                'annee'=>$annee
            ]);

        }
         $totalPris = $ressourceRepository->getTotalMontantPris($periode);
        $st = $operationEmploieRepository->getTotalMontantAPayer($periode);
        $p = $periodeRepository->find($periode);
//        $titre = $periodeRepository->find($periode)->getMois()->getLibelle().' '.$periodeRepository->find($periode)->getExercice()->getAnnee();
        $titre = 'Tableau mensuel des ressources et emploies du mois de'.$p->getMois()->getLibelle().' '.$p->getExercice()->getAnnee() ;
        return $this->render('emploie/operations/index.html.twig',[
            'ressources' => $ressourceRepository->findBy(['periode'=>$periode]),
            'operation_emploies' => $operationEmploieRepository->findBy(['periode'=>$periode]),
            'sommes'=>$operationEmploieRepository->getTotalRetenue($periode),
            'form' => $form->createView(),
            'mois'=>(int) date('n'),
            'annee'=>(int) date('Y'),
            'periode'=>$periode,
            'totalPris'=>$totalPris,
            'st'=>$st,
            'labelPeriode'=>$titre
        ]);
    }

    #[Route('/operations/autorisation_modification/{id}', name: 'app_emploie_operations_autorisation_modification',
        methods: ['GET','POST'])]
    public function autorisatioModification(Request $request,OperationEmploie $operationEmploie,OperationEmploieRepository
    $operationEmploieRepository,EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(DemandeModificationEmploieType::class, $operationEmploie);
        $form->handleRequest($request);

//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->persist($operationEmploie);
//            $operationEmploie->setAutorisation(OperationsStatut::DEMANDE_MODIFICATION);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_emploie_caisse_index', [], Response::HTTP_SEE_OTHER);
//        }
//        return $this->render('emploie/operations/autorisation_modification.html.twig',[
//            'operation_emploies' => $operationEmploieRepository->findAll(),
//            'form' => $form,
//        ]);
        return $this->handleEmploieForm($request, $entityManager, $operationEmploie, false);
    }
    #[Route('/{id}/modal-form', name: 'app_emploie_modification_modal_edit_form', methods: ['GET'])]
    public function modalEditForm(OperationEmploie $emploie): Response
    {
        return $this->renderModalForm($emploie, true);
    }
    /**
     * Fonction privée pour gérer le formulaire d'emploi (ajout/modification)
     */
    private function handleEmploieForm(Request $request, EntityManagerInterface $entityManager, OperationEmploie $emploie, bool $isEdit): Response
    {
        $form = $this->createForm(DemandeModificationEmploieType::class, $emploie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$isEdit) {
                $emploie->setAutorisation(OperationsStatut::DEMANDE_MODIFICATION);
                $entityManager->persist($emploie);
            }
            $entityManager->flush();

            // Si c'est une requête AJAX, retourner du JSON
            if ($request->isXmlHttpRequest()) {
                return $this->createSuccessResponse($emploie, $isEdit);
            }

            return $this->redirectToRoute('app_emploie_operations_index', [], Response::HTTP_SEE_OTHER);
        }

        // Si c'est une requête AJAX et qu'il y a des erreurs
        if ($request->isXmlHttpRequest()) {
            return $this->createErrorResponse($form, $emploie, $isEdit);
        }

        // Rendu classique (non-AJAX)
        $template = $isEdit ? 'emploie/operations/autorisation_modification.html.twig' : 'emploie/operations/autorisation_modification.html.twig';
        return $this->render($template, [
            'emploie' => $emploie,
            'form' => $form,
        ]);
    }

    /**
     * Fonction privée pour rendre le formulaire modal
     */
    private function renderModalForm(OperationEmploie $emploie, bool $isEdit): Response
    {
        $form = $this->createForm(DemandeModificationEmploieType::class, $emploie);

        return $this->render('emploie/emploie/_autorisation_form_modal.html.twig', [
            'form' => $form->createView(),
            'emploie' => $emploie,
            'is_edit' => $isEdit
        ]);
    }

    /**
     * Fonction privée pour créer une réponse JSON de succès
     */
    private function createSuccessResponse(OperationEmploie $emploie, bool $isEdit): JsonResponse
    {
        $message = $isEdit ? 'Emploi modifié avec succès' : 'Emploi ajouté avec succès';

        return new JsonResponse([
            'success' => true,
            'message' => $message,
            'emploie' => [
                'id' => $emploie->getId(),
                'libelle' => $emploie->getReferenceManuel()
            ]
        ]);
    }

    /**
     * Fonction privée pour créer une réponse JSON d'erreur
     */
    private function createErrorResponse($form, OperationEmploie $emploie, bool $isEdit): JsonResponse
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        $templateVars = ['form' => $form->createView()];
        if ($isEdit) {
            $templateVars['emploie'] = $emploie;
            $templateVars['is_edit'] = true;
        }

        return new JsonResponse([
            'success' => false,
            'errors' => $errors,
            'form' => $this->renderView('emploie/emploie/_autorisation_form_modal.html.twig', $templateVars)
        ]);
    }

    public function modification(Request $request, OperationEmploie $operationEmploie,EntityManagerInterface $entityManager): Response
    {
        return $this->renderModalForm(new OperationEmploie(), false);
    }

    #[Route('/export-pdf-emploie/', name: 'app_emploie_printf')]
    public function exportPdf(Request $request, OperationEmploieRepository $operationEmploieRepository, ExportService $exportService):
    Response
    {
        $mois = (int)$request->query->get('mois');
        $annee = (int)$request->get('annee');
        $emploies= $operationEmploieRepository->findAll();
       // dd($mois, $annee);
        if ($mois && $annee) {
            $emploies=  $operationEmploieRepository->getOperationEmploiePeriode($mois, $annee);
        }

        // Récupérer les opérations
        $name ='emploie';
      //  $name =$courrier->getReferenceInterne();

        // Utilisation du service ExportService pour exporter en PDF
        return $exportService->exportPdf('emploie/export/emploie.html.twig', [
            'operation_emploies' => $emploies,
        ],  $name.'.pdf');
    }
}
