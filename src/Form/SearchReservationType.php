<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('submitedAtBegining', DateType::class, [
                'label'=>'Soumis apres le',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'html5' => false,
                'attr'=>[
                    'data-toggle' => 'datetimepicker',
                    'data-target' => '#reservation_beginingAt',
                ],
                'required' => false
            ])
            ->add('submitedAtEnd', DateType::class, [
                'label' => 'Soumis avant le',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'html5' => false,
                'attr'=>[
                    'data-toggle' => 'datetimepicker',
                    'data-target' => '#reservation_endingAt',
                ],
                'required' => false
            ])
            ->add('rangeBegining', DateType::class, [
                'label'=>'Valable apres le',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'html5' => false,
                'attr'=>[
                    'data-toggle' => 'datetimepicker',
                    'data-target' => '#reservation_beginingAt',
                ],
                'required' => false
            ])
            ->add('rangeEnd', DateType::class, [
                'label' => 'Valable avant le',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'html5' => false,
                'attr'=>[
                    'data-toggle' => 'datetimepicker',
                    'data-target' => '#reservation_endingAt',
                ],
                'required' => false
            ])
            ->add('canceled', CheckboxType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'required' => false
            ])
            ->add('validated', CheckboxType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'required' => false
            ])
            ->add('haveCommentaire', CheckboxType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'required' => false
            ])
            ->add('user', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // none
        ]);
    }
}
