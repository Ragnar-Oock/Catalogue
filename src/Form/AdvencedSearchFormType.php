<?php

namespace App\Form;

use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvencedSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', SearchType ::class, [
                'label' => 'Rechercher',
                'attr' => [
                    'placeholder' => 'Rechercher un auteur, un editeur, un titre...'
                ],
                'required' => false
            ])
            ->add('publisheAfter', DateType::class, [
                'label'=>'Edité après le',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'html5' => false,
                'attr'=>[
                    'data-toggle' => 'datetimepicker',
                    'data-target' => '#advenced_search_form_publisheAfter',
                ],
                'required' => false
            ])
            ->add('publishedBefore', DateType::class, [
                'label' => 'Edité avant le',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'html5' => false,
                'attr'=>[
                    'data-toggle' => 'datetimepicker',
                    'data-target' => '#advenced_search_form_publishedBefore',
                ],
                'required' => false
            ])
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'label' => 'Type de support',
                'required' => false
            ]) //collection => get types
            ->add('title', TextType::class, [
                'label' => 'Le titre contient :',
                'help' => 'Recherche un titre, un sous titre ou un titre alternatif contenant la valeur saisie',
                'required' => false
            ]) // text LIKE in title, subtitle and alt title
            ->add('author', TextType::class, [
                'label' => 'Nom de l\'auteur',
                'required' => false
            ]) // text LIKE
            ->add('editor', TextType::class, [
                'label' => 'Nom de l\'éditeur',
                'required' => false
            ]) // text LIKE
            // advenced
            ->add('issn', TextType::class, [
                'required' => false
            ]) //exact matche
            ->add('isbn', TextType::class, [
                'required' => false
            ]) //exact matche
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
