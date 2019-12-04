<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//Permet de modifier les catégories a condition qu'on s'appelle Root. Comme il ne peut y en avoir qu'un, c'est sécurisé
//Toutes les fonctions et les twig ont été créer automatiquement et reprennent les base pour créer/modifier/supprimer les éléments présent dans la table Categories
/**
 * @Route("/categories")
 */
class CategoriesController extends AbstractController
{
    /**
     * @Route("/", name="categories_index", methods={"GET"})
     */
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        if($this->getUser()!=null) {
            if ($this->getUser()->getUsername() == "Root") {
                return $this->render('categories/index.html.twig', [
                    'categories' => $categoriesRepository->findAll(),
                ]);
            }
        }
        return $this->redirectToRoute('annonces');
    }

    /**
     * @Route("/new", name="categories_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if($this->getUser()!=null) {
            if($this->getUser()->getUsername()=="Root") {
                $category = new Categories();
                $form = $this->createForm(CategoriesType::class, $category);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($category);
                    $entityManager->flush();

                    return $this->redirectToRoute('categories_index');
                }

                return $this->render('categories/new.html.twig', [
                    'category' => $category,
                    'form' => $form->createView(),
                ]);
            }
        }
        return $this->redirectToRoute('annonces');
    }

    /**
     * @Route("/{id}", name="categories_show", methods={"GET"})
     */
    public function show(Categories $category): Response
    {
        if($this->getUser()!=null) {
            if ($this->getUser()->getUsername() == "Root") {
                return $this->render('categories/show.html.twig', [
                    'category' => $category,
                ]);
            }
        }

        return $this->redirectToRoute('annonces');
    }

    /**
     * @Route("/{id}/edit", name="categories_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Categories $category): Response
    {
        if($this->getUser()!=null)
        {
            if ($this->getUser()->getUsername() == "Root") {
                $form = $this->createForm(CategoriesType::class, $category);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $this->getDoctrine()->getManager()->flush();

                    return $this->redirectToRoute('categories_index');
                }

                return $this->render('categories/edit.html.twig', [
                    'category' => $category,
                    'form' => $form->createView(),
                ]);
            }
        }
        return $this->redirectToRoute('annonces');
    }

    /**
     * @Route("/{id}", name="categories_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Categories $category): Response
    {
        if($this->getUser()!=null) {
            if ($this->getUser()->getUsername() == "Root") {
                if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($category);
                    $entityManager->flush();
                }
            }
        }


        return $this->redirectToRoute('categories_index');
    }
}
