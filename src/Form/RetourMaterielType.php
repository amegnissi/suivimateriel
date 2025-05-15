<?php
namespace App\Form;

use App\Entity\SortieMateriel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RetourMaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateRetour', DateTimeType::class, [
                'label' => 'Date de Retour',
                'widget' => 'single_text',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('etatRetour', TextareaType::class, [
                'label' => 'État / Description du Retour',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Décrivez l’état du matériel au retour...',
                    'class' => 'form-control',
                    'rows' => 4,
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SortieMateriel::class,
        ]);
    }
}