<?php

namespace App\Form;

use App\Entity\Materiel;
use App\Entity\Assurance;
use App\Entity\TypeAssurance;
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
            ->add('vehicule', EntityType::class, [
                'class' => Materiel::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->innerJoin('m.marque', 'marque')
                        ->where('marque.estVehicule = :isVehicule')
                        ->setParameter('isVehicule', true);
                },
                'choice_label' => function (Materiel $materiel) {
                    return $materiel->getMarque()->getLibelle() . ' - ' . $materiel->getImmatriculation();
                },
                'label' => 'Véhicule',
                'placeholder' => 'Sélectionner un véhicule',
                'required' => true,
            ])
            ->add('typeAssurance', EntityType::class, [
                'class' => TypeAssurance::class,
                'choice_label' => 'libelle',
                'label' => 'Type d\'opération',
                'placeholder' => 'Sélectionner un type',
                'required' => true,
            ])
            ->add('montantPaye', NumberType::class, [
                'label' => 'Montant payé (FCFA)',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                'required' => false,
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
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
