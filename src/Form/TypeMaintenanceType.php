<?php

namespace App\Form;

use App\Entity\TypeMaintenance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class TypeMaintenanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class, [
                'label' => 'Nom du Type de Maintenance',
                'attr' => [ 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le libellé ne peut pas être vide.',
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Le libellé ne peut pas dépasser 255 caractères.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TypeMaintenance::class,
        ]);
    }
}
