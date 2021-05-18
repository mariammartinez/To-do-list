<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Todolist;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use App\Repository\TodolistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToDoListController extends AbstractController
{
    /**
     * @Route("/todolist", name="todolist")
     * @param TodolistRepository $repo
     * @return Response
     */
    public function index(TodolistRepository $repo, Request $request): Response
    {


        $repo = $this->getDoctrine()->getRepository(Todolist::class);
        $todolists = $repo->findAll();

        $item =  new Item();
        $item->setCreateAt(new \DateTime('now'));


        $form = $this->createForm(ItemType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $list = $todolists[0] ;
            $item->setToDoList($list);

            $todolist = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($todolist);
            $entityManager->flush();

            return $this->redirectToRoute('todolist');
        }
        return $this->render('todolist/index.html.twig', [
            'todolists' => $todolists,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param int $id
     * @param TodolistRepository $todolistRepository
     * @return Response
     * @Route("/todolist/{id}", name="todolist_show")
     */
    public function show(int $id, TodolistRepository $todolistRepository, Request $request): Response
    {
        $todolist = $todolistRepository
            ->find($id);

        $itemo =  new Item();
        $itemo->setCreateAt(new \DateTime('now'));


        $form = $this->createForm(ItemType::class, $itemo);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $list = $todolist;
            $itemo->setToDoList($list);

            $todolist = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($todolist);
            $entityManager->flush();

            return $this->redirectToRoute('todolist_show', [
                'id' => $id
            ]);
        }

        return $this->render('todolist/show.html.twig', [
            'todolist' => $todolist,
            'form' => $form->createView()

        ]);
    }

    /**
     *@Route("/todolistchecked/{id}", name="todolist_checked")
     *
     */
    public function checked(int $id, ItemRepository $itemRepository){
        $item = $itemRepository
            ->find($id);
        $item->setChecked(true);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($item);
        $entityManager->flush();

      return $this->redirectToRoute('todolist_show', [
            'id' => $item->getToDoList()->getId()
        ]);


    }
}
