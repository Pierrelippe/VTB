<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Photo;
use App\Form\AnnoncesType;
use App\Form\PhotoAnnoncesType;
use App\Form\PhotoType;
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
        //On crée ses formulairs
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
            //dd($_POST);
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
     * @Route("/editPhoto/{id}", name="annonces_photo_edit", methods={"GET","POST"})
     */
    public function editAnnoncePhoto($id,Request $request,ObjectManager $manager)
    {

        //Annonce correspondant à l'ID envoyé
        $annonce=$manager->getRepository(Annonces::class)->find($id);
        //Une nouvelle photo
        $photo = new Photo();
        //User connecté
        $user=$this->getUser();
        //form de l'annonce
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        //Si l'annonce n'appartient pas à l'utilisateur connecté, alors il est redirigé vers les annonces
        if($annonce->getUser()->getId() !=$user->getId()){
            return $this->redirectToRoute('annonces');
        }

        if($form->isSubmitted() && $form->isValid())
        {
            $file=$photo->getLink();
            $filename = pathinfo( $file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = preg_replace('/[^A-Za-z0-9]/', "",$filename).'.'.$file->guessExtension();
            $file->move($this->getParameter('photo_directory'),$safeFilename);
            $photo->setLink($safeFilename);
            $manager->persist($photo);
            $annonce->addPhoto($photo);
            $manager->flush();
            return $this->redirectToRoute('annonces');
        }

        return $this->render('annonces/createAnnonce.html.twig', [
            'form' =>  $form->createView(),
        ]);

    }

    /**
     * @Route("/removePhoto/{id}", name="annonces_photo_remove")
     */
    public function removeAnnoncePhoto($id,ObjectManager $manager)
    {
        //Récupére User connecté
        $user=$this->getUser();
        //Photo correspondant à l'ID envoyé
        $photo=$manager->getRepository(Photo::class)->find($id);
        dump($photo);
        //On récupére l'annonce correspondant a cette photo
        $annonce=$photo->getAnnoncePhoto();


        //Si l'annonce de la photo n'appartient pas à l'utilisateur connecté, alors il est redirigé vers les annonces
        if($annonce->getUser()->getId() !=$user->getId()) {
            return $this->redirectToRoute('annonces');
        }

        $manager->persist($photo);
        $annonce->removePhoto($photo);
        $manager->remove($photo);
        $manager->flush();
        return $this->redirectToRoute('annonces');

    }

    /**
     * @Route("/delete/{id}", name="annonces_delete")
     */
    public function deleteAnnonce($id,ObjectManager $manager)
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
