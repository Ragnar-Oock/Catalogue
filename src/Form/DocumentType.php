<?php

namespace App\Form;

use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label'=> 'Titre',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez fournir un titre',
                    ]),
                ],
            ])
            ->add('subtitle', TextType::class, [
                'label'=> 'Sous titre',
                'required' => false
            ])
            ->add('alttitle', TextType::class, [
                'label'=> 'Titre Alternatif',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
