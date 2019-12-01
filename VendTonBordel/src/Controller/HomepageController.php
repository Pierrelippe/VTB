<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        //Si on est connecté, on va directement sur la page de profil
        if ($this->getUser()!= null)
        {

            return $this->redirectToRoute('user_profil');
        }

        //Sinon on va sur la page "homepage" qui sert de page de connexion
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
        ]);


    }
}
