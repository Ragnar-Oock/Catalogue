<?php

namespace App\Form;

use App\Entity\Collec;
use App\Entity\Document;
use App\Entity\Edition;
use App\Entity\Editor;
use App\Repository\CollecRepository;
use App\Repository\DocumentRepository;
use App\Repository\EditorRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive;

class EditionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('document', EntityType::class, [
                'class' => Document::class,
                'query_builder' => function (DocumentRepository $dr) {
                    return $dr->createQueryBuilder('d')
                        ->orderBy('d.title', 'ASC');
                },
                'required'=> true
            ])
            ->add('editor', EntityType::class, [
                'class' => Editor::class,
                'query_builder' => function (EditorRepository $edr) {
                    return $edr->createQueryBuilder('ed')
                        ->orderBy('ed.name', 'ASC');
                },
                'required'=> true

            ])
            ->add('pages', NumberType::class, [
                'constraints' => [
                    new Positive([
                        'message' => 'Le nombre de page doit être superieur à zero ou vide',
                    ]),
                    ],
                'required'=> false
            ])
            ->add('tome', TextType::class, [
                'required'=> false
            ])
            ->add('publishedAt', DateType::class, [
                'label' => 'Publié le',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'html5' => false,
                'attr' => [
                    'data-toggle' => 'datetimepicker',
                    'data-target' => '#edition_publishedAt',
                ],
                'required' => false
            ])
            ->add('inventoryNumber')
            ->add('type')
            ->add('collecs', EntityType::class, [
                'class' => Collec::class,
                'query_builder' => function (CollecRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->orderBy('c.title', 'ASC');
                },
                'multiple'  => true,
                'required' => false,
                'help' => 'Maintenez <code>MAJ</code> effoncer pour selectionner plusieurs collections',
                'help_html' => true
            ])

            ->add('issn')
            ->add('isbn')
            ->add('notes')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Edition::class,
        ]);
    }
}
