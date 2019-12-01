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

        return $this->render('annonces/annonce.html.twig', [
            //on met toute les annonces dans une variable
            'annonces' =>  $product = $manager->getRepository(Annonces::class)->findAll(),
            'form' =>  $form->createView()
        ]);
    }

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
