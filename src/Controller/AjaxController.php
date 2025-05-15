<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\MaterielRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route(path: '/ajax')]
class AjaxController extends AbstractController
{
    #[Route(path: '/materiel/{type}/{id}', name: 'ajax_filtre_quartier')]
    public function index(Request $request, MaterielRepository $materielRepository): Response
    {


        $type = $request->get('type');
        $id = $request->get('id');
//        dd($type, $id,$materielRepository->findBy(['type' => 1]));
        if ($type === 'typemateriel') {
            $Resultat =$materielRepository->findBy(['type' => $id]);

        }
        else{
            $Resultat = null;
        }
//        if($Resultat) section
//        {
//            $response = array("success" => true,
//            // "code"=>$code,
//           // 'prix'=>$produit->getPrix(),
//           // 'label' => $Resultat->getLibelle(),
//            'value' => $Resultat['id']
//
//             );
//        }
        $data = [];
        foreach ($Resultat as $item) {
//            if ($type === 'section') {
//                $label = $item->getFullName();
//            } else {
//                if ($item->getLibelle()) {
//                    $label = $item->getLibelle();
//                } else {
//                    $label = $item->getLibelle();
//                }
//            }

            $data[] = [

                'label' => $item->getMarque()->getLibelle().'  - ' . $item->getImmatriculation(),
                'value' => $item->getId(),

            ];

        }
        // dd($type,$id,$Resultat,$data);

        return new Response(json_encode($data));
        // return json_encode($Resultat);

        dd($type, $id, $Resultat);
        return $this->render('ajax/index.html.twig', [
            'controller_name' => 'AjaxController',
        ]);
    }

}
