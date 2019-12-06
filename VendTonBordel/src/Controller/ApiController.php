<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Categories;
use App\Form\AnnoncesType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use App\Repository\AnnoncesRepository;


/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{

    /**
     * @Route("/GET/annonce")
     */
    public function index(ObjectManager $manager)
    {

        $encoders = [new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(), new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        if(isset($_GET['id'])){

            $data= $manager->getRepository(Annonces::class)->find($_GET['id']);

            $jsonContent = $serializer->serialize($data, 'json', ['circular_reference_handler' => function ($object) {
                return $object->getId();
            },  AbstractNormalizer::ATTRIBUTES => ['user'=>['username'],'name','description','prix','date','user' => ['link']]]);
            return new JsonResponse($jsonContent);
        }
        if(isset($_GET['categorie'])){
            $category = $this->getDoctrine()->getRepository(Categories::class)->findOneBy(['name' =>  $_GET['categorie']]);
            $data= $manager->getRepository(Annonces::class)->findAdByCategory($category);
            $jsonContent = $serializer->serialize($data, 'json', ['circular_reference_handler' => function ($object) {
                return $object->getId();
            },  AbstractNormalizer::ATTRIBUTES => ['user'=>['username'],'name','description','prix','date','user' => ['link']]]);
            return new JsonResponse($jsonContent);
        }
        if(isset($_GET['search'])){
            $data= $manager->getRepository(Annonces::class)->findAdByName($_GET['search']);
            $jsonContent = $serializer->serialize($data, 'json', ['circular_reference_handler' => function ($object) {
                return $object->getId();
            }, AbstractNormalizer::ATTRIBUTES => ['user'=>['username'],'name','description','prix','date','user' => ['link']]]);
            dump($jsonContent);
            return new JsonResponse($jsonContent);
        }

        $data=$manager->getRepository(Annonces::class)->findAll();
        $jsonContent = $serializer->serialize($data, 'json', ['circular_reference_handler' => function ($object) {
            return $object->getId();
        }, AbstractNormalizer::ATTRIBUTES => ['user'=>['username'],'name','description','prix','date','user' => ['link']]]);
        return new JsonResponse($jsonContent);

    }


}
