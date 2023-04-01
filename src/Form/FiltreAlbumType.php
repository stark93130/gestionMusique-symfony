<?php

namespace App\Form;

use App\Entity\Artiste;
use App\Model\FiltreAlbum;
use App\Repository\ArtisteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreAlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                'attr'=>[
                    'placeholder'=>"saisir une partie du nom de l'album recherché"
                ],
                'required' =>false,
                'label'=>'Rechercher un album'
            ])
            ->add('artiste', EntityType::class, [
                'class' => Artiste::class,
                'query_builder' => function (ArtisteRepository $repository) {
                    return $repository->listeTriAlphabetique();
                },
                'choice_label' => 'nom',
                'required' =>false,
                'label'=>"Rechercher un artiste"

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'method' => 'GET',
            'data_class'=>FiltreAlbum::class
        ]);
    }

}
