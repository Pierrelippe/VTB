<?php

namespace App\Form;

use App\Entity\Annonces;
use App\Entity\Categories;
use App\Entity\Photo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnoncesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //On ne met pas la date et l'user car ils seront créer dans le controller
            //Car l'user est l'utilisateur connecté et la date est la date de création
                //Les commentaires ci-dessous sont ceux de bootstrap et expliquent bien à quoi ça sert
            ->add('name', TextType::class)
            ->add('description',TextareaType::class)
            ->add('prix')
            ->add('categorie',EntityType::class,[
                // looks for choices from this entity
                'class' => Categories::class,

                // uses the User.username property as the visible option string
                'choice_label' => function($categorie){
                return $categorie->getName();
                } ])

           // ->add('phototek',FileType::class, [
            //    'mapped' => false,
           // ])
          /*  ->add('photo',PhotoType::class,  array(
               ' mapped'=>false
            ))*/
        /*EntityType::class,[
                // looks for choices from this entity
                'class' => Photo::class,
                // uses the User.username property as the visible option string
                'choice_label' => 'link',    ])*/


            ->add('Envoyer', SubmitType::class)
    ;}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonces::class,
        ]);
    }
}
