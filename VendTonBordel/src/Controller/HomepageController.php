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

        if ($this->getUser()!= null) {

           return $this->redirectToRoute('user_profil');
        }

        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
        ]);


    }
}
