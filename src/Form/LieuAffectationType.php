<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\SocieteService;
use App\Entity\LieuAffectation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class LieuAffectationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Interne' => 'interne',
                    'Externe' => 'externe',
                ],
                'placeholder' => 'Choisissez un type'
            ])
            ->add('societeService', EntityType::class, [
                'class' => SocieteService::class,
                'choice_label' => 'nom',
                'required' => false,
                'placeholder' => 'Choisissez un client concerné',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LieuAffectation::class,
        ]);
    }
}
