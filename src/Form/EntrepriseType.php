<?php

namespace App\Form;

use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l’entreprise',
                'attr' => ['class' => 'form-control']
            ])
            ->add('sigle', TextType::class, [
                'label' => 'SIGLE',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Numéro de téléphone',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('ifu', TextType::class, [
                'label' => 'IFU',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('siteWeb', TextType::class, [
                'label' => 'Site Web',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('gerant', TextType::class, [
                'label' => 'Nom du gérant',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('kilometrage', NumberType::class, [
                'label' => 'Kilométrage',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('delaiAssurance', IntegerType::class, [
                'label' => 'Délai avant expiration assurance (jours)',
                'required' => false,
            ])
            ->add('delaiTVM', IntegerType::class, [
                'label' => 'Délai avant expiration TVM (jours)',
                'required' => false,
            ])
            ->add('delaiVisiteTechnique', IntegerType::class, [
                'label' => 'Délai avant expiration visite technique (jours)',
                'required' => false,
            ])
            ->add('logo', FileType::class, [
                'label' => 'Logo (PNG, JPG, JPEG)',
                'required' => false,
                'data_class' => null,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (PNG, JPG, JPEG)',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
        ]);
    }
}
