<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Todolist;
use App\Form\ItemType;
use App\Form\TodolistType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(Request $request): Response
    {
        $todolist = new Todolist();
        $todolist->setCreatedAt(new \DateTime('now'));

        $form = $this->createForm(TodolistType::class, $todolist);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $todolist = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($todolist);
            $entityManager->flush();

            return $this->redirectToRoute('todolist_show', [
                'id' => $todolist->getId()
            ]);
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView()
        ]);


    }

}
