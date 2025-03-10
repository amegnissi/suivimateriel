<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/employes')]
class EmployeController extends AbstractController
{
    #[Route('/', name: 'employes_index', methods: ['GET'])]
    public function index(EmployeRepository $employeRepository): Response
    {
        $employes = $employeRepository->findAll();

        return $this->render('employes/index.html.twig', [
            'employes' => $employes,
        ]);
    }

    #[Route('/new', name: 'employes_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $employe = new Employe();
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photoFile = $form->get('photoFile')->getData();
            if ($photoFile) {
                $newFilename = uniqid().'.'.$photoFile->guessExtension();
                $photoFile->move($this->getParameter('uploads_directory'), $newFilename);
                $employe->setPhoto($newFilename);
            }

            $copieCarteIdFile = $form->get('copieCarteIdFile')->getData();
            if ($copieCarteIdFile) {
                $newFilename = uniqid().'.'.$copieCarteIdFile->guessExtension();
                $copieCarteIdFile->move($this->getParameter('uploads_directory'), $newFilename);
                $employe->setCopieCarteId($newFilename);
            }

            $copieDiplomeFile = $form->get('copieDiplomeFile')->getData();
            if ($copieDiplomeFile) {
                $newFilename = uniqid().'.'.$copieDiplomeFile->guessExtension();
                $copieDiplomeFile->move($this->getParameter('uploads_directory'), $newFilename);
                $employe->setCopieDiplome($newFilename);
            }

            $certificatAcquiteVisuelFile = $form->get('certificatAcquiteVisuelFile')->getData();
            if ($certificatAcquiteVisuelFile) {
                $newFilename = uniqid().'.'.$certificatAcquiteVisuelFile->guessExtension();
                $certificatAcquiteVisuelFile->move($this->getParameter('uploads_directory'), $newFilename);
                $employe->setCertificatAcquiteVisuel($newFilename);
            }
        
            $entityManager->persist($employe);
            $entityManager->flush();

            return $this->redirectToRoute('employes_index');
        }

        return $this->render('employes/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'employes_show', methods: ['GET'])]
    public function show(Employe $employe): Response
    {
        return $this->render('employes/show.html.twig', [
            'employe' => $employe,
        ]);
    }

    #[Route('/{id}/edit', name: 'employes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Employe $employe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('employes_index');
        }

        return $this->render('employes/edit.html.twig', [
            'form' => $form->createView(),
            'employe' => $employe,
        ]);
    }

    #[Route('/{id}', name: 'employes_delete', methods: ['POST'])]
    public function delete(Request $request, Employe $employe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $employe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($employe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('employes_index');
    }
}