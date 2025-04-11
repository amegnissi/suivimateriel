<?php

namespace App\Form;

use App\Entity\Evenements;
use App\Entity\Personne;
use App\Entity\TypeEvenements;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('description')
            ->add('contact', EntityType::class, [
                'class'=>Personne::class,
                'required' => false,
                'multiple' => true,
                'placeholder' => 'Sélectionner une ou plusieurs produits',
                'choice_label' => 'fullName',
//                'choice_value' => 'id',
                'attr' => [
//                    'data-controller' => 'select2'
                    'class' => 'select2-ajax', // Classe pour l'initialisation de Select2
                    'data-url' => '/ajax/api-produits'
                ],
                'mapped' => false,
            ])
            ->add('dateDebut', null, [
                'widget' => 'single_text',
            ])
            ->add('heureDebut', null, [
                'widget' => 'single_text',
            ])
            ->add('DateFin', null, [
                'widget' => 'single_text',
            ])
            ->add('heureFin', null, [
                'widget' => 'single_text',
            ])
            ->add('rappelAutomatique')
            ->add('recurrence', ChoiceType::class, [
                'choices' => [
                    'Aucune' => null,
                    'Quotidienne' => 'FREQ=DAILY;INTERVAL=1',
                    'Hebdomadaire' => 'FREQ=WEEKLY;INTERVAL=1',
                    'Mensuelle' => 'FREQ=MONTHLY;INTERVAL=1',
                    'Annuelle' => 'FREQ=YEARLY;INTERVAL=1',
                ]
            ])
            ->add('finRecurrence',null,[
                'widget' => 'single_text',
            ])
            ->add('priorite', ChoiceType::class, [
                'choices' => [
                    'Faible' => 1,
                    'Moyenne' => 2,
                    'Élevée' => 3,
                ]
            ])
//            ->add('Observation')
            ->add('lieu')
            ->add('type', EntityType::class, [
                'class' => TypeEvenements::class,
                'choice_label' => 'libelle',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenements::class,
        ]);
    }
}
