<?php

namespace App\Form\Emploie;

use App\Entity\Emploie\Ressource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RessourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $moisChoices = [
            'Janvier' => 1, 'Février' => 2, 'Mars' => 3, 'Avril' => 4,
            'Mai' => 5, 'Juin' => 6, 'Juillet' => 7, 'Août' => 8,
            'Septembre' => 9, 'Octobre' => 10, 'Novembre' => 11, 'Décembre' => 12,
        ];
        $currentYear = (int) date('Y');
        $anneeChoices = array_combine(
            range(2020, $currentYear + 10),
            range(2020, $currentYear + 10)
        );
        $builder
            ->add('mois', ChoiceType::class, [
                'choices' => $moisChoices,
                'label' => 'Mois',
                'placeholder' => 'Choisissez un mois',
            ])
            ->add('annee', ChoiceType::class, [
                'choices' => $anneeChoices,
                'label' => 'Année',
                'placeholder' => 'Choisissez une année',
            ])
            ->add('sourcesPaiement')
            ->add('montantPris')
            ->add('montantRestant')
            ->add('reference')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ressource::class,
        ]);
    }
}
