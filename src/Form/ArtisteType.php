<?php

namespace App\Form;

use App\Entity\Artiste;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtisteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'placeholder' => "Saisir le nom de l'artiste"
                ]
            ])
            ->add('description', CKEditorType::class, [
                'attr' => [
                    'placeholder' => "Saisir la description de l'artiste"
                ]
            ])
            ->add('site', UrlType::class, [
                'attr' => [
                    'placeholder' => "https://sitedelartiste.com"
                ]
            ])
            ->add('image', TextType::class,[
                'required'   => false
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'solo' => 0,
                    'groupe' => 1
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artiste::class,
        ]);
    }
}
