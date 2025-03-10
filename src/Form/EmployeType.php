<?php

namespace App\Form;

use App\Entity\Poste;
use App\Entity\Employe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Nom'
        ])
        ->add('prenom', TextType::class, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Prénom'
        ])
        ->add('telephonePersonnel', TextType::class, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Téléphone Personnel'
        ])
        ->add('telephoneCorporate', TextType::class, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Téléphone Corporate'
        ])
        ->add('email', EmailType::class, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Email'
        ])
        ->add('poste', EntityType::class, [
            'class' => Poste::class,
            'choice_label' => 'libelle',
            'attr' => ['class' => 'form-control'],
            'label' => 'Poste'
        ])
        ->add('photoFile', FileType::class, [
            'required' => false,
            'mapped' => false,  // Ne pas mapper avec l'entité
            'attr' => ['class' => 'form-control'],
            'label' => 'Photo'
        ])
        ->add('copieCarteIdFile', FileType::class, [
            'required' => false,
            'mapped' => false,
            'attr' => ['class' => 'form-control'],
            'label' => 'Copie Carte ID'
        ])
        ->add('copieDiplomeFile', FileType::class, [
            'required' => false,
            'mapped' => false,
            'attr' => ['class' => 'form-control'],
            'label' => 'Copie Diplôme'
        ])
        ->add('certificatAcquiteVisuelFile', FileType::class, [
            'required' => false,
            'mapped' => false,
            'attr' => ['class' => 'form-control'],
            'label' => 'Certificat Acuité Visuelle'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
