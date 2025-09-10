<?php

namespace App\Form\Emploie;

use App\Entity\Emploie\Caisse;
use App\Entity\Emploie\Emploie;
use App\Entity\Emploie\OperationEmploie;
use App\Form\ApplicationType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperationEmploieType extends ApplicationType
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
            ->add('dateOperation',DateType::class,[
                'label' => 'Date de l\'opération',
                'required'=>true
            ])
            // ->add('mois', ChoiceType::class, [
            //     'choices' => $moisChoices,
            //     'label' => 'Mois',
            //     'placeholder' => 'Choisissez un mois',
            // ])
            // ->add('annee', ChoiceType::class, [
            //     'choices' => $anneeChoices,
            //     'label' => 'Année',
            //     'placeholder' => 'Choisissez une année',
            // ])
            ->add('montantAPayer',IntegerType::class,[
                'attr' => ['id' => 'montant_a_payer'],
                'label' => 'Montant à payer',
            ])
            ->add('netAPayer',IntegerType::class,[
                'mapped' => false,
                'label' => 'Net à payer',
                 'attr' => ['id' => 'net_a_payer', 'readonly' => true]
            ])
            ->add('retenue',IntegerType::class,[
                'required' => false,
                'attr' => ['id' => 'retenue']
            ])
            ->add('designation', EntityType::class, [
                'class' => Emploie::class,
                'choice_label' => 'libelle',
                'placeholder' => 'Choisissez une designation',
                'label' => 'Désignation'
            ])
            ->add('caisse', EntityType::class, [
                'class' => Caisse::class,
                'choice_label' => 'libelle',
                'placeholder' => 'Choisissez une caisse',
                'label' => 'Caisse Bénéficiaire',
                'attr' => ['id' => 'caisse_beneficiaire']
            ])
            ->add('referenceManuel',TextType::class,$this->getConfiguration('Référence ','référence',[
                'required' => false,
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OperationEmploie::class,
        ]);
    }
}
