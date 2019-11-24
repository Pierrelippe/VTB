<?php

namespace App\Controller;

use App\Form\RegistrationType;
use App\Form\UserUpdatePasswordType;
use App\Form\UserUpdateType;
use App\Service\ProfilGestion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="user_profil")
     */
    public function index()
    {
        if($this->getUser()==null)
            dump("Il n y a ps de user");

        $user= $this->getUser();
        //vérifie si le profile regarder est le notre. Ce booléen nous servira à savoir si il faut afficher "Modifier"
        $IsMyProfile=true;
        return $this->render('user_profil/index.html.twig', [
            'Auth' => $user,
            'User'=>$user,
            'IsMyProfile'=>$IsMyProfile
        ]);
    }

    /**
     * @Route("/profil/{id}", name="user_profil_Id")
     */
    public function profilId(int $id,EntityManagerInterface $em)
    {
        if($this->getUser()==null)
            dump("Il n y a ps de user");
        //vérifie si le profile regarder(mis en paramètre est le notre. Ce booléen nous servira à savoir si il faut afficher "Modifier"
        $IsMyProfile=false;
        if($id == $this->getUser()->getId()){
            $IsMyProfile=true;
        }

        return $this->render('user_profil/index.html.twig', [
            'Auth' => $this->getUser(),
            'User' => $em->getRepository(User::class)->find($id),
            'IsMyProfile'=>$IsMyProfile
        ]);
    }

    /**
     * @Route("/profil_update_page", name="profil_update")
     */

    public function profilUpdate(Request $request, EntityManagerInterface $em) {

        $form = $this->createForm(UserUpdateType::class, $this->getUser());

        $form->handleRequest($request); // On récupère le formulaire envoyé dans la requête
        if ($form->isSubmitted() && $form->isValid()) { // on véfifie si le formulaire est envoyé et si il est valide

            $article = $form->getData(); // On récupère l'article associé
            $em->persist($article); // on le persiste
            $em->flush(); // on save
            return $this->redirectToRoute('user_profil');

        }
        return $this->render('user_profil/profilUpdate.html.twig', ['form' => $form->createView()]); // on envoie ensuite le formulaire au template

    }

    /**
     * @Route("/profil_update_page_password", name="profil_update_password")
     */
    public function profilUpdatePassword(Request $request, EntityManagerInterface $em,UserPasswordEncoderInterface $encoder) {

        $form = $this->createForm(UserUpdatePasswordType::class, $this->getUser());

        $form->handleRequest($request); // On récupère le formulaire envoyé dans la requête

        if ($form->isSubmitted() && $form->isValid()) { // on véfifie si le formulaire est envoyé et si il est valide
            //Crypter les mots de passes
            $hash = $encoder->encodePassword($this->getUser(),$this->getUser()->getPassword());
            $this->getUser->setPassword($hash);
            $article = $form->getData(); // On récupère l'article associé
            $em->persist($article); // on le persiste
            $em->flush(); // on save
            return $this->redirectToRoute('user_profil');
        }
        return $this->render('user_profil/profilUpdatePassword.html.twig', ['form' => $form->createView()]); // on envoie ensuite le formulaire au template

    }

}
