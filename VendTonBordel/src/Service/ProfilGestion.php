<?php

namespace App\Service;


use App\Entity\NameGen;
use Doctrine\ORM\EntityManagerInterface;

class ProfilGestion
{





    //Suprimer Profil
    private function DeleteProfil(EntityManagerInterface $em,$id)
    {

        $product= $em->getRepository(NameGen::class)->find($id);
        $em->remove($product);
        $em->flush();
        //LOG OUT
    }



}