<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'app_employe')]
    public function index(EmployeRepository $employeRepository): Response
    {
        // $employes = $employeRepository->findAll();

        //SELECT * from employe ORDER BY nom ASC
        $employes = $employeRepository->findby([], ['nom' => 'ASC']);

        return $this->render('/employe/index.html.twig', [
            'employes' => $employes
        ]);
    }

    #[Route('/employe/new', name: 'new_employe')]
    #[Route('/employe/{id}/edit', name: 'edit_employe')]
    public function new_edit(Employe $employe = null, Request $request, EntityManagerInterface $entityManager): Response
    {   
        if(!$employe) {
            $employe = new Employe();
        }
        
        $form = $this->createForm(EmployeType::class, $employe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $employe = $form->getData();

            // ... perform some action, such as saving the task to the database
            //persist = prepare / flush = execute en PDO
            $entityManager->persist($employe);
            $entityManager->flush();

            return $this->redirectToRoute('app_employe');
        }
        
        return $this->render('employe/new.html.twig', [
            'formAddEmploye' => $form,
        ]);
    }

    #[Route('/employe/{id}/delete', name: 'delete_employe')]
    public function delete(Employe $employe, EntityManagerInterface $entityManager)
    {   
        $entityManager->remove($employe);
        $entityManager->flush();

        return $this->redirectToRoute('app_employe');
    }

    #[Route('/employe/{id}', name: 'show_employe')]
    public function show(Employe $employe): Response
    {
        return $this->render('employe/show.html.twig', [
            'employe' => $employe
        ]);
    }
}
