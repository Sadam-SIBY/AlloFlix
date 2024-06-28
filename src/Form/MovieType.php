<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Durée'
            ])
            ->add('releaseDate', DateType::class, [
                'label' => 'Date de réalisation'
            ])
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'Film' => 'Film',
                    'Série' => 'Série',
                ],

                'placeholder' => 'Choisissez',
            ])

            ->add('synopsis', TextType::class, [
                'label' => 'Synopsis'
            ])
            ->add('poster', UrlType::class, [
                'label' => 'Image'
            ])
            ->add('rating', ChoiceType::class, [
                'choices'  => [
                    'Excellent' => 5,
                    'Très bon' => 4,
                    'Bon' => 3,
                    'Peut mieux faire' => 2,
                    'A éviter' => 1,
            ],
                'placeholder' => 'Votre choix ...'
            ])
            ->add('summary', TextareaType::class, [
                'label' => 'Résumé'
            ])
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
        'choice_label' => 'name',
        'multiple' => true,
        'expanded' => true
            ])

            ->add('Ajouter', SubmitType::class, [
                'attr' => ['class' => 'save',
                            'class'=> 'btn btn-success '],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
