<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', SearchType ::class, [
                'attr' => [
                    'placeholder' => 'Rechercher un auteur, un editeur, un titre...'
                ],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'get',
            'action' => '/recherche',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return ''; // return an empty string here
    }
}
