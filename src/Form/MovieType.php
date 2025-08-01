<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Movie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title')
        ->add('synopsis')
        ->add('dateSortie', DateType::class, [
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
        ])
        ->add('genre', EntityType::class, [
            'class' => Genre::class,
            'choice_label' => 'name',
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
