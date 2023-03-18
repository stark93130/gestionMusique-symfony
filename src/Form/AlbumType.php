<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Artiste;
use App\Entity\Style;
use App\Repository\ArtisteRepository;
use App\Repository\StyleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('date', IntegerType::class)
            ->add('image', TextType::class)
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
                ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
        ]);
    }
}
