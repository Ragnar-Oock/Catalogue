<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('submitedAtBegining', DateTimeType::class, [
                'label'=>'Soumis après le',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy HH:mm',
                'html5' => false,
                'attr'=>[
                    'data-toggle' => 'datetimepicker',
                    'data-target' => '#search_reservation_submitedAtBegining',
                ],
                'required' => false
            ])
            ->add('submitedAtEnd', DateTimeType::class, [
                'label' => 'Soumis avant le',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy HH:mm',
                'html5' => false,
                'attr'=>[
                    'data-toggle' => 'datetimepicker',
                    'data-target' => '#search_reservation_submitedAtEnd',
                ],
                'required' => false
            ])
            ->add('rangeBegining', DateTimeType::class, [
                'label'=>'Valable après le',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy HH:mm',
                'html5' => false,
                'attr'=>[
                    'data-toggle' => 'datetimepicker',
                    'data-target' => '#search_reservation_rangeBegining',
                ],
                'required' => false
            ])
            ->add('rangeEnd', DateTimeType::class, [
                'label' => 'Valable avant le',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy HH:mm',
                'html5' => false,
                'attr'=>[
                    'data-toggle' => 'datetimepicker',
                    'data-target' => '#search_reservation_rangeEnd',
                ],
                'required' => false
            ])
            ->add('validated', CheckboxType::class, [
                'label' => 'Validées',
                'required' => false
            ])
            ->add('canceled', CheckboxType::class, [
                'label' => 'Annulées',
                'required' => false
            ])
            ->add('pending', CheckboxType::class, [
                'label' => 'En attente de validation',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // none
        ]);
    }
}
