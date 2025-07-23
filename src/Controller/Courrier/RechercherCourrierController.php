<?php

declare(strict_types=1);

namespace App\Controller\Courrier;

use App\Data\RechercheData;
use App\Form\Courrier\RechercheCourrierType;
use App\Repository\Courrier\CourrierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RechercherCourrierController extends AbstractController
{
    #[Route('/rechercher-courrier', name: 'app_courrier_recherche')]
    public function index(Request $request, CourrierRepository $courrierRepository): Response
    {
        $search = new RechercheData();

        $form = $this->createForm(RechercheCourrierType::class, $search);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $courriers = $courrierRepository->recherche($form->getData());
            if ($courriers) {
                foreach ($courriers as $courrier) {
                    if ($courrier->getDateArivee()) {
                        $date = $courrier->getDateArivee()->format('d/m/Y');
                    } else {
                        $date = null;
                    }
                    $response[] = [
                        'type' => $courrier->getTypeCourrier()->getLibelle(),
                        'reference' => $courrier->getReferenceInterne(),
                        'date' => $date,
                        'objet' => $courrier->getObjet(),
                        'partenaire' => $courrier->getPartenaire()->getNomOuRaisonSocial(),
                        'fichier' => $request->getSchemeAndHttpHost() . $request->getBasePath()
                            . $courrier->getFichier()
                        ,
                        'showUrl' => $this->generateUrl('app_courrier_show', ['id' => $courrier->getId()]),
                        'editUrl' => $this->generateUrl('app_courrier_edit', ['id' => $courrier->getId()])
                    ];
                }
            } else {
                $response = null;
            }
            return new JsonResponse($response);
        }
        return $this->render('rechercher_courrier/index.html.twig', [
            'form' => $form,
        ]);
    }
}
