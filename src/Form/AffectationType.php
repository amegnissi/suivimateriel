<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\Materiel;
use App\Entity\Affectation;
use App\Entity\SocieteService;
use App\Entity\LieuAffectation;
use App\Repository\EmployeRepository;
use App\Repository\MaterielRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AffectationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateAffectation', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'Affectation',
            ])
            ->add('employe', EntityType::class, [
                'class' => Employe::class,
                'query_builder' => function (EmployeRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->leftJoin('e.affectations', 'a')
                        ->where('e.depart IS NULL')
                        ->andWhere('a.id IS NULL');
                },
                'choice_label' => function (Employe $employe) {
                    return $employe->getPrenom() . ' ' . $employe->getNom();
                },
                'attr' => ['class' => 'select2'], // Ajout de Select2
                'label' => 'Employé',
                'placeholder' => 'Choisissez un Employé',
                'required' => false,
            ])


            ->add('materiel', EntityType::class, [
                'class' => Materiel::class,
                'query_builder' => function (MaterielRepository $mr) {
                    return $mr->createQueryBuilder('m')
                        ->leftJoin('m.affectations', 'a')
                        ->where('a.id IS NULL');
                },
                'choice_label' => function (Materiel $materiel) {
                    return $materiel->getMarque()->getLibelle(). ' - ' . $materiel->getImmatriculation();
                },
                'attr' => ['class' => 'select2'], // Ajout de Select2
                'label' => 'Matériel',
                'placeholder' => 'Choisissez un Matériel',
                'required' => false,
            ])
            ->add('societe', EntityType::class, [
                'class' => SocieteService::class,
                'choice_label' => 'nom',
                'label' => 'Société',
                'placeholder' => 'Choisissez un Société',
                'required' => false,
            ])
            ->add('lieuAffectation', EntityType::class, [
                'class' => LieuAffectation::class,
                'choice_label' => 'nom',
                'placeholder' => 'Sélectionnez un lieu ou service',
                'attr' => ['class' => 'form-control select2'],
                'choice_attr' => function (LieuAffectation $lieu) {
                    return ['data-type' => $lieu->getType()];
                },
            ])
            ->add('codification',TextType::class, [
                'mapped' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Affectation::class,
        ]);
    }
}
