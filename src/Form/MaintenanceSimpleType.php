<?php

namespace App\Form;

use App\Entity\Maintenance;
use App\Entity\Materiel;
use App\Entity\TypeMaintenance;
use App\Repository\MaterielRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MaintenanceSimpleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateIntervention', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'intervention',
                'required' => true,
            ])
            ->add('typeMaintenance', EntityType::class, [
                'class' => TypeMaintenance::class,
                'choice_label' => 'libelle',
                'label' => 'Type de maintenance',
                'placeholder' => 'Sélectionnez un type',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('technicien', TextType::class, [
                'label' => 'Technicien ou structure charge de maintenance',
                'required' => false,
            ])
            ->add('actionsRealisees', TextAreaType::class, [
                'label' => 'Actions réalisées',
                'required' => false,
                'attr' => ['placeholder' => 'Actions réalisées lors de l’intervention ou de la maintenance', 'rows' => 4],
            ])
            ->add('piecesUtilisees', TextAreaType::class, [
                'label' => 'Pièces utilisées / remplacées',
                'required' => false,
                'attr' => ['placeholder' => 'Pièces utilisées (si matériel remplacé)', 'rows' => 4],
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
            ->add('cout', NumberType::class, [
                'label' => 'Coût (en FCFA)',
                'required' => false,
            ])
            ->add('preuve', FileType::class, [
                'label' => 'Preuve (facture, photo...)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'application/pdf'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image ou un PDF valide.',
                    ])
                ],
            ])
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
