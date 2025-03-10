<?php

namespace App\Form;

use App\Entity\Affectation;
use App\Entity\Maintenance;
use Symfony\Component\Form\AbstractType;
use App\Repository\AffectationRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MaintenanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('affectation', EntityType::class, [
                'class' => Affectation::class,
                'query_builder' => function (AffectationRepository $ar) {
                    return $ar->createQueryBuilder('a')
                        ->innerJoin('a.materiel', 'm')
                        ->where('m.statut != :statut')
                        ->setParameter('statut', 2); // Exclure les matériels déjà en maintenance
                },
                'choice_label' => function (Affectation $affectation) {
                    return $affectation->getMateriel()->getLibelle() . ' - ' . $affectation->getSociete()->getNom();
                },
                'placeholder' => 'Sélectionnez une affectation',
                'label' => 'Matériel affecté',
                'required' => true,
            ])
            ->add('typeMainteance', ChoiceType::class, [
                'choices' => [
                    'Révision' => 'Révision',
                    'Réparation' => 'Réparation',
                    'Entretien' => 'Entretien',
                ],
                'label' => 'Type de maintenance',
                'placeholder' => 'Sélectionnez un type',
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Maintenance::class,
        ]);
    }
}