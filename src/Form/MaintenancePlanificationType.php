<?php

namespace App\Form;

use App\Entity\Maintenance;
use App\Entity\TypeMaintenance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MaintenancePlanificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeMaintenance', EntityType::class, [
                'class' => TypeMaintenance::class,
                'choice_label' => 'libelle',
                'label' => 'Type de maintenance',
                'placeholder' => 'Sélectionnez un type',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('datePlanification', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date prevu pour l\'intervention',
                'required' => true,
            ])

            ->add('actionsRealisees', TextAreaType::class, [
                'label' => 'Actions prevues',
                'required' => false,
                'attr' => ['placeholder' => 'Actions à réaliser lors de la future  maintenance',
                    'rows' => 4],
            ])
            ->add('description', TextAreaType::class, [
                'label' => 'Observations',
                'required' => false,
                'attr' => ['placeholder' =>'Autres observations','rows' => 4],
            ])

//            ->add('kilometrageActuel', NumberType::class, [
//                'label' => 'Kilométrage actuel',
//                'required' => false,
//            ])
//            ->add('cout', NumberType::class, [
//                'label' => 'Coût (en FCFA)',
//                'required' => false,
//            ])
//            ->add('preuve', FileType::class, [
//                'label' => 'Preuve (facture, photo...)',
//                'mapped' => false,
//                'required' => false,
//                'constraints' => [
//                    new File([
//                        'maxSize' => '5M',
//                        'mimeTypes' => ['image/jpeg', 'image/png', 'application/pdf'],
//                        'mimeTypesMessage' => 'Veuillez télécharger une image ou un PDF valide.',
//                    ])
//                ],
//            ])
//            ->add('kilometragePrevisionnel', HiddenType::class, [
//                'mapped' => false,
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Maintenance::class,
        ]);
    }
}
