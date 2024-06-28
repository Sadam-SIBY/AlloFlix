<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchMovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Ici je veux creer une barre de recherche donc on va creer un Form avec un seul champ (car c'est UNE barre de recherche)
            $builder
                ->add('movie', EntityType::class, [ // EntityType 
                    'class' => Movie::class, // fait reference a l'entité Movie
                    'placeholder' => 'Recherer un film ...', // Le placeholder
                    'choice_label' => 'title', // On va afficher quel propriété de Movie => le title (le titre du film)
                    'autocomplete' => true, // Autocompletion activé
                ])
                ->add('submit', SubmitType::class, [ // Le bouton submit pour effectuer la recherche
                    'label' => 'Rechercher'
                ])
            ;
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
