<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\DepartEmploye;
use App\Repository\EmployeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DepartEmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('employe', EntityType::class, [
                'class' => Employe::class,
                'query_builder' => function (EmployeRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->where('e.depart IS NULL'); // Filtre les employés sans départ
                },
                'choice_label' => function (Employe $employe) {
                    return $employe->getPrenom() . ' ' . $employe->getNom();
                },
                'label' => 'Employé concerné',
                'placeholder' => 'Sélectionner un employé',
            ])
            ->add('dateDepart', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de départ',
                'required' => false,
            ])
            ->add('motif', TextareaType::class, [
                'label' => 'Motif du départ',
                'required' => false,
                'attr' => ['rows' => 3],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DepartEmploye::class,
        ]);
    }
}