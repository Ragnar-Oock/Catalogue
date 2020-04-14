<?php

namespace App\Form;

use App\Entity\Edition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('issn')
            ->add('isbn')
            ->add('inventoryNumber')
            ->add('publishedAt')
            ->add('tome')
            ->add('pages')
            ->add('notes')
            ->add('disponibility')
            ->add('document')
            ->add('authors')
            ->add('type')
            ->add('collecs')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Edition::class,
        ]);
    }
}
