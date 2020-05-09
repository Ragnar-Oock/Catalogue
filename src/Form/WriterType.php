<?php

namespace App\Form;

use App\Entity\Writer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WriterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('participationType')
            ->add('author')
            ->add('save', SubmitType::class, ['label' => 'Enregistrer et retourner a l\'edition'])
            ->add('saveAndAddMore', SubmitType::class, ['label' => 'Enregister et ajouter d\'autres participants'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Writer::class,
        ]);
    }
}
