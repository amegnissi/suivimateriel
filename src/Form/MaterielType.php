<?php

namespace App\Form;

use App\Entity\Marque;
use App\Entity\Materiel;
use App\Entity\TypeMateriel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextareaType::class, [
                'required' => false,
                'label' => 'Description',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('numeroSerie', TextType::class, [
                'label' => 'Numéro de série',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('date_acquisition', DateType::class, [
                'required' => false,
                'label' => 'Date d\'acquisition',
                'widget' => 'single_text',    
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'date',
                ],
            ])
            ->add('immatriculation', TextType::class, [
                'label' => 'Immatriculation',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('modele', TextType::class, [
                'label' => 'Modèle',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('type', EntityType::class, [
                'class' => TypeMateriel::class,
                'required' => false,
                'choice_label' => 'libelle',
                'label' => 'Type de matériel',
                'placeholder' => 'Choisissez un type',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('marque', EntityType::class, [
                'class' => Marque::class,
                'required' => false,
                'choice_label' => 'libelle',
                'label' => 'Marque',
                'placeholder' => 'Choisissez une marque',
                'attr' => [
                    'class' => 'form-control',
                    'onchange' => 'checkImmatriculationField()',
                ],
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image du matériel (jpg, png, gif)',
                'mapped' => false, // Ne pas lier ce champ à l'entité Materiel directement
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (jpeg, png, gif)',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Materiel::class,
        ]);
    }
}