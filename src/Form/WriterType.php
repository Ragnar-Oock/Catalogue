<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\ParticipationType;
use App\Entity\Writer;
use App\Repository\AuthorRepository;
use App\Repository\ParticipationTypeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WriterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('participationType', EntityType::class, [
                'label' => 'Type de participation',
                'class' => ParticipationType::class,
                'query_builder' => function (ParticipationTypeRepository $pr) {
                    return $pr->createQueryBuilder('p')
                        ->orderBy('p.title', 'ASC');
                },
                'required'=> true
            ])
            ->add('author', EntityType::class, [
                'label' => 'Auteur',
                'class' => Author::class,
                'query_builder' => function (AuthorRepository $ar) {
                    return $ar->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
                },
                'required'=> true
            ])
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
