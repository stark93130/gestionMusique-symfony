<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Artiste;
use App\Entity\Style;
use App\Repository\ArtisteRepository;
use App\Repository\StyleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile',FileType::class,[
                'mapped'=>false,
                'required'=>false,
                'label'=>'charger la pochette',
                'attr'=>[
                    'accept'=>".jpg,.png"
                ],
                'row_attr'=>[
                    'class'=>'d-none'
                    ],
                'constraints' => [
                    new Image([
                        'maxSize' => '1024k',
                        'maxSizeMessage'=>'la taille du fichier doit être de 1024K '
                    ])
                ]

            ])
            ->add('image',HiddenType::class)
            ->add('nom', TextType::class)
            ->add('date', IntegerType::class)
            ->add('artiste', EntityType::class, [
                'class' => Artiste::class,
                'query_builder' => function (ArtisteRepository $repository) {
                    return $repository->listeTriAlphabetique();
                },
                'choice_label' => 'nom'
            ])
            ->add('styles', EntityType::class, [
                    'class' => Style::class,
                    'query_builder' => function (StyleRepository $repository) {
                        return $repository->listeTriAlphabetique();
                    },
                    'multiple' => true,
                    'expanded' => false,
                    'by_reference' => false,
                    'attr'=>[
                        'class'=>"selectStyles"
                    ]
                ])
            ->add("morceaux",CollectionType::class,[
               'entry_type'=>MorceauType::class,
                'label'=>false,
                'allow_add'=>true,
                'allow_delete'=>true,
                'prototype'=>true,
                'by_reference'=>false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
        ]);
    }
}
