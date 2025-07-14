<?php

namespace App\Form\Courrier;

use App\Entity\Courrier\Courrier;
use App\Entity\Courrier\NatureCourrier;
use App\Entity\Courrier\Partenaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CourrierRechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        $builder
            ->add('partenaire', EntityType::class, [
                'class' => Partenaire::class,
                'choice_label' => 'NomOuRaisonSocial',
                'placeholder' => "Sélectionnez un partenaire",
                'required' => false,
            ])
            ->add('nature', EntityType::class, [
                'class' => NatureCourrier::class,
                'choice_label' => 'libelle',
            ])
            ->add('signataire')
            ->add('referenceInterne', null, [
                'label' => 'Référence',
                'attr' => [
                    'placeholder' => "Saisissez la Référence du courrier",
                ],

                'required' => false,
            ])
            ->add('dateArivee', null, [
                'widget' => 'single_text',
                'label' => 'Date de départ',
            ])
            ->add('dateSignature', null, [
                'widget' => 'single_text',
            ])
            ->add('objet')

//            ->add('Statut', EntityType::class, [
//                'class' => Statut::class,
//                'choice_label' => 'libelle',
//            ])
//            ->add('typeCourrier', EntityType::class, [
//                'class' => TypeCourrier::class,
//                'choice_label' => 'id',
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Courrier::class,
            'type_courrier' => '',
        ]);

        $resolver->setAllowedTypes('type_courrier', 'string');
    }
}
