<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Form\AnnoncesType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/annonces")
 */
class AnnoncesController extends AbstractController
{
    /**
     * @Route("/", name="annonces")
     */
    public function index( Request $request, ObjectManager $manager )
    {

        //On crée une nouvelle annonce
        $annonce=new Annonces();
        //On crée son formulairs
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);
        //Mettre la personne connecté dans une variable pour voir lesquelles sont nos annonces
        $user=$this->getUser();
        //Si le formulaire est "Submit" et les termes ont bien été remplie
        if($form->isSubmitted() && $form->isValid())
        {
            //On dis que c'est l'utilisateur connecté qui détient l'annonce
            $annonce->setUser($this->getUser());
            //On met la date qu'il est lorsqu'on arrive dans cette partie du code
            $annonce->setDate(new \DateTime());

            $manager->persist($annonce);

            $manager->flush();
            //On va sur la page des annonces
            return $this->redirectToRoute('annonces');
        }

        //RESEARCH
/*
        if (isset($_POST['submit']) AND !empty($_POST['adSearch']))
        {
            $nameSearch = htmlspecialchars($_POST['adSearch']);
        }
        else
        {
            $nameSearch = "";
        }

        if (isset($_POST['submit']) AND !empty($_POST['category']))
        {
            $categorySearch = $_POST['category'];
        }
        else
        {
            $categorySearch = "";
        }

        $categories = $this->getDoctrine()->getRepository(Annonces::class)->getCategory();

        if ($categorySearch == "")
        {
            $annonces = $this->getDoctrine()->getRepository(Annonces::class)
                ->findAdByName($nameSearch);
        }
        else {
            $annonces = $this->getDoctrine()->getRepository(Annonces::class)
                ->findAdByCategory($categorySearch);
        }*/



        return $this->render('annonces/annonce.html.twig', [
            //on met toute les annonces dans une variable
            'annonces' =>  $product = $manager->getRepository(Annonces::class)->findAll(),
            'form' =>  $form->createView(),
            'auth'=> $user,
          /*  'research.html.twig',
            array("compteur" => count($annonces),
                "liste" => $annonces,
                "listecategories" => $categories
                )*/
        ]);
    }


    /**
     * @Route("/{id}", name="annonce_Id")
     */
    public function annonceId($id, Request $request, ObjectManager $manager )
    {

        //On crée une nouvelle annonce
        $annonce=$manager->getRepository(Annonces::class)->find($id);;

        //Mettre la personne connecté dans une variable pour voir lesquelles sont nos annonces
        $user=$this->getUser();


        return $this->render('annonces/annonceId.html.twig', [
            'a' =>  $annonce,
            'auth'=> $user
        ]);
    }
    /**
     * @Route("/edit/{id}", name="annonces_edit", methods={"GET","POST"})
     */
    public function editAnnonce($id,Request $request,ObjectManager $manager)
    {
        //dd($_POST);
        //Annonce correspondant à l'ID envoyé
        $annonce=$manager->getRepository(Annonces::class)->find($id);
        //User connecté
        $user=$this->getUser();
        //form de l'annonce
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        //Si l'annonce n'appartient pas à l'utilisateur connecté, alors il est redirigé vers les annonces
        if($annonce->getUser()->getId() !=$user->getId()){
            return $this->redirectToRoute('annonces');
        }

        if($form->isSubmitted() && $form->isValid())
        {
            dd($_POST);
            //On récupére ce qu'il y a dans le form puis on le met sur la base de donnée
            $artticle=$form->getData();
            $manager->persist($artticle);
            $manager->flush();
            //On va sur la page des annonces
            return $this->redirectToRoute('annonces');
        }

        return $this->render('annonces/createAnnonce.html.twig', [
            'form' =>  $form->createView(),
        ]);

    }
    /**
     * @Route("/delete/{id}", name="annonces_delete")
     */
    public function deleteAnnonce($id,Request $request,ObjectManager $manager)
    {
        //Annonce correspondant à l'ID envoyé
        $annonce=$manager->getRepository(Annonces::class)->find($id);

        //User connecté
        $user=$this->getUser();


        //Si l'annonce n'appartient pas à l'utilisateur connecté, alors il est redirigé vers les annonces
        if($annonce->getUser()->getId() !=$user->getId()){
            return $this->redirectToRoute('annonces');
        }

        //On supprime l'annonce et on met l'info sur la base de donnée
        $manager->remove($annonce);
        $manager->flush();

        //On va sur la page des annonces
        return $this->redirectToRoute('annonces');




    }

    //Route pas utiliser à supprimer si c'est toujours le cas d'ici mardi
    /**
     * @Route("/new", name="newAnnonce")
     */

    public function newAnnonce(Request $request, ObjectManager $manager)
    {
        //On crée une nouvelle annonce
        $annonce=new Annonces();
        //On crée son formulairs
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);
        //Si le formulaire est "Submit" et les termes ont bien été remplie
        if($form->isSubmitted() && $form->isValid())
        {
            //On dis que c'est l'utilisateur connecté qui détient l'annonce
            $annonce->setUser($this->getUser());
            //On met la date qu'il est lorsqu'on arrive dans cette partie du code
            $annonce->setDate(new \DateTime());
            //on met la category

            $manager->persist($annonce);

            $manager->flush();
            //On va sur la page des annonces
            return $this->redirectToRoute('annonces');
        }

        return $this->render('annonces/createAnnonce.html.twig', [
            'form' =>  $form->createView(),

        ]);
    }

}
