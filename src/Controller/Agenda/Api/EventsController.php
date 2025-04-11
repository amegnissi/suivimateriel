<?php

declare(strict_types=1);

namespace App\Controller\Agenda\Api;

use App\Entity\Evenements;
use App\Repository\EvenementsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class EventsController extends AbstractController
{
    #[Route('/events')]
    public function evenementsEnCours(EvenementsRepository $evenementsRepository): Response
    {
        $events = $evenementsRepository->findAll();

        $data = array_map(function ($event) {

            $contact= [];
            if ($event->getReunions()){
                foreach ($event->getReunions() as $contacts)
                $contact [] = [
                    'idContact' => $contacts->getParticipant()->getId(),
                    'nom' => $contacts->getParticipant()->getFullName(),
                    'telephone' => $contacts->getParticipant()->getTelephone(),
                    'email' => $contacts->getParticipant()->getEmail(),
                    'information' => $contacts->getParticipant()->getFullInformations(),
                ];
            }
            return [
                'id' => $event->getId(),
                'title' => $event->getLibelle(),
                'allDay'=> false,
                'start' =>
                (new \DateTime(
                    $event->getDateDebut()->format('Y-m-d') . ' ' . $event->getHeureDebut()->format('H:i:s')
                ))->format('Y-m-d\TH:i:s'),
//                'startTime'=>$event->getHeureDebut()->format('H:i'),

                'end' =>
                (new \DateTime(
                    $event->getDateFin()->format('Y-m-d') . ' ' . $event->getHeureFin()->format('H:i:s')
                ))->format('Y-m-d\TH:i:s'),
//                'endTime'=>$event->getHeureFin()->format('H:i'),
                'color' => $event->getType()->getCouleur(),
                'backgroundcolor'=>$event->getType()->getCouleur(),
                'location'=> $event->getLieu(),
                'description' => $event->getDescription(),
                'contact'=> $contact,
                'heureFn'=>$event->getHeureFin()->format('H:i:s'),
                'heureDeb'=>$event->getHeureDebut()->format('H:i:s'),

            ];
        }, $events);
        return $this->json($data);
    }


    #[Route('/events/{id}',name: 'events_details')]
    public function detailsEvents(Evenements $event): Response {
        $contact= [];
        if ($event->getReunions()){
            foreach ($event->getReunions() as $contacts)
                $contact [] = [
                    'idContact' => $contacts->getParticipant()->getId(),
                    'nom' => $contacts->getParticipant()->getFullName(),
                    'telephone' => $contacts->getParticipant()->getTelephone(),
                    'email' => $contacts->getParticipant()->getEmail(),
                    'information' => $contacts->getParticipant()->getFullInformations(),
                ];



        }
        $data = [
            'id' => $event->getId(),
            'title' => $event->getLibelle(),
            'allDay'=> false,
            'start' =>
                (new \DateTime(
                    $event->getDateDebut()->format('Y-m-d') . ' ' . $event->getHeureDebut()->format('H:i:s')
                ))->format('Y-m-d\TH:i:s'),
//                'startTime'=>$event->getHeureDebut()->format('H:i'),

            'end' =>
                (new \DateTime(
                    $event->getDateFin()->format('Y-m-d') . ' ' . $event->getHeureFin()->format('H:i:s')
                ))->format('Y-m-d\TH:i:s'),
//                'endTime'=>$event->getHeureFin()->format('H:i'),
            'color' => $event->getType()->getCouleur(),
            'backgroundcolor'=>$event->getType()->getCouleur(),
            'location'=> $event->getLieu(),
            'description' => $event->getDescription(),
            'contact'=> $contact

        ];
        return $this->json($data);
    }
}
