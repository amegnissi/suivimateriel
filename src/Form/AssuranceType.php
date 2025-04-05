<?php

namespace App\Form;

use App\Entity\Materiel;
use App\Entity\Assurance;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class AssuranceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('materiel', EntityType::class, [
                'class' => Materiel::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->innerJoin('m.marque', 'marque')
                        ->where('marque.estVehicule = :isVehicule')
                        ->setParameter('isVehicule', true); // Seulement les matériels de type véhicule
                },
                'choice_label' => function (Materiel $materiel) {
                    return $materiel->getMarque()->getLibelle() . ' - ' . $materiel->getImmatriculation();
                },
                'label' => 'Matériel',
                'placeholder' => 'Sélectionner un véhicule',
                'required' => true,
            ])
            ->add('typeAssurance', ChoiceType::class, [
                'choices' => [
                    'Assurance' => 'assurance',
                    'TVM' => 'tvm',
                    'Visite Technique' => 'visite_technique',
                ],
                'label' => 'Type d\'opération',
                'required' => true,
            ])
            ->add('montantPaye', NumberType::class, [
                'label' => 'Montant payé (FCFA)',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateAssuranceDebut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début d\'assurance',
                'required' => false,
            ])
            ->add('dateAssuranceFin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin d\'assurance',
                'required' => false,
            ])
            ->add('dateVisiteTechniqueDebut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début de visite technique',
                'required' => false,
            ])
            ->add('dateVisiteTechniqueFin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin de visite technique',
                'required' => false,
            ])
            ->add('dateTVMDebut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début TVM',
                'required' => false,
            ])
            ->add('dateTVMFin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin TVM',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Assurance::class,
        ]);
    }
}
