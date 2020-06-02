<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UptPswType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('verifyPassword', PasswordType::class, [
                'label' => 'Entrez votre mot de passe actuel'
            ])
            ->add('password', RepeatedType::class, [
                "type" => PasswordType::class,
                "first_options" => [
                    "label" => "Entrez votre nouveau mot de passe"
                ],
                "second_options" => [
                    "label" => "Confirmez votre nouveau mot de passe"
                ],
                "invalid_message" => "Vos deux mots de passe ne correspondent pas"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
