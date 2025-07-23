<?php

namespace App\Form\Courrier;

use App\Data\RechercheData;
use App\Entity\Courrier\NatureCourrier;
use App\Entity\Courrier\Partenaire;
use App\Repository\courrier\PrioriteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheCourrierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('objet', null,[
                'attr'=>[
                    'placeholder' => "Saisissez l'objet du courrier",
                ],
                'label' => 'Objet',
            ])
            ->add('reference',null,[
                'attr'=>[
                    'placeholder' => "Saisissez la référence du courrier",
                ],
                'label'=>'Référence',
            ])
            ->add('nature', EntityType::class, [
                'class' => NatureCourrier::class,
                'choice_label' => 'libelle',
                'placeholder' => "Sélectionnez la nature du courrier",
                'required' => false,
            ])
            ->add('dateDebut',DateType::class,[
                'required' => false,
                'label'=>'Date de debut',
            ])
            ->add('dateFin',DateType::class,[
                'required' => false,
                'label'=>'Date de fin',
            ])
            ->add('partenaire', EntityType::class, [
                'class' => Partenaire::class,
//                'query_builder' => function (PrioriteRepository $repo) {
//                    return $repo->createQueryBuilder('m')
//                        ->in('m.estSorti = false');
//                },
                'choice_label' => 'NomOuRaisonSocial',
                'placeholder' => "Sélectionnez un partenaire",
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RechercheData::class,
        ]);
    }
}
