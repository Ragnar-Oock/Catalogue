<?php

namespace App\Form;

use App\Entity\Collec;
use App\Entity\Document;
use App\Entity\Edition;
use App\Entity\Editor;
use App\Entity\Fond;
use App\Entity\Type;
use App\Repository\CollecRepository;
use App\Repository\DocumentRepository;
use App\Repository\EditorRepository;
use App\Repository\TypeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class EditionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('document', EntityType::class, [
                'label' => 'Document',
                'class' => Document::class,
                'query_builder' => function (DocumentRepository $dr) {
                    return $dr->createQueryBuilder('d')
                        ->orderBy('d.title', 'ASC');
                },
                'required'=> true
            ])
            ->add('editor', EntityType::class, [
                'label' => 'Editeur',
                'class' => Editor::class,
                'query_builder' => function (EditorRepository $edr) {
                    return $edr->createQueryBuilder('ed')
                        ->orderBy('ed.name', 'ASC');
                },
                'required'=> true

            ])
            ->add('pages', NumberType::class, [
                'label' => 'Nombre de pages',
                'constraints' => [
                    new Positive([
                        'message' => 'Le nombre de page doit être superieur à zero ou vide',
                    ]),
                ],
                'required'=> false
            ])
            ->add('tome', TextType::class, [
                'label' => 'Numero de tome',
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
            ->add('inventoryNumber', NumberType::class, [
                'label' => 'Numéro d\'inventaire',
                'constraints' => [
                    new Positive([
                        'message' => 'Le numéro d\'invetaire doit être superieur à zero',
                    ]),
                    new NotBlank([
                        'message' => 'Le numéro d\'inventaire ne peut pas être vide' 
                    ])
                ],
            ])
            ->add('type', EntityType::class, [
                'label' => 'Type de support',
                'class' => Type::class,
                'query_builder' => function (TypeRepository $tr) {
                    return $tr->createQueryBuilder('t')
                        ->orderBy('t.title', 'ASC');
                },
            ])
            ->add('collecs', EntityType::class, [
                'label' => 'Collections',
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
            ->add('fond', EntityType::class, [
                'label' => 'Fond documentaire',
                'class' => Fond::class,
                'required'=> true,
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
