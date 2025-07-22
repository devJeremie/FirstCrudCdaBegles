<?php

namespace App\Controller;

use App\Entity\Crud;
use App\Form\CrudType;
use App\Repository\CrudRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function homePage(CrudRepository $crudRepo): Response
    {
        //en parametre on mettra le crudRepository
        $datas = $crudRepo->findAll(); 
        // $datas = $entityManager
        //     ->getRepository(Crud::class)
        //     ->findAll();
        return $this->render('home_page/homePage.html.twig', [
            'controller_name' => 'HomePageController',
            'datas' => $datas,
        ]);
    }

    #[Route('/create', name: 'app_create_form')]
    public function create_form(Request $request, EntityManagerInterface $entityManager ): Response
    {
        $crud = new Crud();
        $form = $this->createForm(CrudType::class, $crud);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the form submission, save the data to the database
            
            $entityManager->persist($crud);
            $entityManager->flush();

            $this->addFlash('notice', 'Soumission réussie !');

            // Redirect or return a response after successful submission
            return $this->redirectToRoute('app_home_page');
        }

        return $this->render('form/createForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/update/{id}', name: 'app_home_page_edit')]
    public function update_form($id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $crud = $entityManager->getRepository(Crud::class)->find($id);
        $form = $this->createForm(CrudType::class, $crud);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the form submission, save the data to the database

            $entityManager->persist($crud);
            $entityManager->flush();

            $this->addFlash('notice', 'Soumission réussie !');

            // Redirect or return a response after successful submission
            return $this->redirectToRoute('app_home_page');
        }

        return $this->render('form/updateForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_home_page_delete')]
    public function delete_form($id, EntityManagerInterface $entityManager): Response
    {
        $crud = $entityManager->getRepository(Crud::class)->find($id);
       
            $entityManager->remove($crud);
            $entityManager->flush();

            $this->addFlash('notice', 'Suppression réussie !');
        

        return $this->redirectToRoute('app_home_page');
    }
}
