<?php

namespace App\Form\Courrier;

use App\Entity\Courrier\AffectationCourrier;
use App\Entity\Courrier\Courrier;
use App\Entity\Courrier\Statut;
use App\Entity\Employe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AffectationsCourrierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('dateAffectation', null, [
//                'widget' => 'single_text',
//            ])
            ->add('dateLimiteTraitement', null, [
                'widget' => 'single_text',
            ])
            ->add('fichier', FileType::class, [
                'label' => 'Fichier (PDF file)',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '5024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        ],
                        'mimeTypesMessage' => 'Veuillez revoir le format du document joint pdf, word',
                    ])
                ],
            ])

//            ->add('destinataire', EntityType::class, [
//                'class' => Employe::class,
//                'choice_label' => 'fullName',
//                'multiple' => true,
//                'expanded' => true,
//            ])
            ->add('observation')

//            ->add('statut', EntityType::class, [
//                'class' => Statut::class,
//                'choice_label' => 'id',
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AffectationCourrier::class,
        ]);
    }
}
