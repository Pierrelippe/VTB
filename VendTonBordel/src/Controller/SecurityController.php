<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="security_registration")
     */
    public function registration(Request $request, ObjectManager $manager,UserPasswordEncoderInterface $encoder)
    {
        $user=new User();
        $photo=new Photo();
        //Créer les formulaires pour le twig
        $form =$this->createForm(RegistrationType::class, $user);
        //Prend les requête mis dans le twig
        $form->handleRequest($request);

        //Si le formulaire est "Submit" et les termes ont bien été remplie
        if($form->isSubmitted() && $form->isValid())
        {
            //Crypter les mots de passes
            $hash = $encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);
            //On le met sur la base de donnée
            $manager->persist($user);

            //On met une photo par défaut
            if($user->getProfilPhoto()== null ) {
                $photo->setLink("defaut.png");
                $manager->persist($photo);
                $user->setProfilPhoto($photo);
            }
            $manager->flush();
            //On va sur la page de connexion
            return $this->redirectToRoute('homepage');

        }
        //On va sur la page de "registration"
        return $this->render('security/registration.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function login(){
        //On va sur la page login
        return $this->render('/');
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(){}//C'est histoire d'avoir une route et une fonction. C'est dans sécurity.yaml que tout se passe



}
