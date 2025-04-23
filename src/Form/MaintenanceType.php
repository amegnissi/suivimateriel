<?php

namespace App\Form;

use App\Entity\Materiel;
use App\Entity\Affectation;
use App\Entity\Maintenance;
use App\Entity\TypeMaintenance;
use App\Repository\MaterielRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class MaintenanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('materiel', EntityType::class, [
                'class' => Materiel::class,
                'query_builder' => function (MaterielRepository $mr) {
                    return $mr->createQueryBuilder('m')
                        ->where('m.statut != :statut')
                        ->setParameter('statut', 2); // Exclure les matériels déjà en maintenance
                },
                'choice_label' => function (Materiel $materiel) {
                    return $materiel->getMarque()->getLibelle() . ' - ' . $materiel->getImmatriculation();
                },
                'placeholder' => 'Sélectionnez un matériel',
                'label' => 'Matériel concerné',
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
            ->add('description', TextType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['rows' => 4],
            ])
            ->add('dateIntervention', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'intervention',
                'required' => true,
            ])
            ->add('kilometrageActuel', NumberType::class, [
                'label' => 'Kilométrage actuel',
                'required' => false,
            ])
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
            ->add('kilometragePrevisionnel', HiddenType::class, [
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Maintenance::class,
        ]);
    }
}