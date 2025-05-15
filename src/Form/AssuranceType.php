<?php

namespace App\Form;

use App\Entity\Materiel;
use App\Entity\Assurance;
use App\Entity\TypeAssurance;
use App\Entity\TypeMateriel;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class AssuranceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
//        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
//        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
        $builder
            ->add('typemateriel', EntityType::class, [
                'class' => TypeMateriel::class,
                'choice_label' => 'libelle',
                'placeholder' => 'Sélectionner le type de materiel',
                'required' => false,
                'mapped' => false,
                'multiple' => false,
                'expanded' => false,
                'label' => 'Type Matériel',
                'priority'=>3


            ],
            );
        $builder->get('typemateriel')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                //  $data = $event->getData();
                $form->getParent()->add('vehicule', EntityType::class, [
                    'class' => Materiel::class,
                    'priority'=> 2,
                    'label' => 'Matériel',
                    'placeholder' => 'Sélectionner un matériel',
                    'choices' => $form->getData()->getMateriels(),
                    'choice_label' => function (Materiel $materiel) {
                        return $materiel->getMarque()->getLibelle() . ' - ' . $materiel->getImmatriculation();
                    },
                ]);
            }
        );
        $builder->addEventListener(FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();
                $vehicule = $data->getVehicule();

                if ($vehicule) {
                    $form->get('typemateriel')->setData($vehicule->getType());
                    $form->add('vehicule', EntityType::class, [
                        'class' => Materiel::class,
                        'label' => 'Matériel',
                        'priority'=> 2,
                        'placeholder' => 'Sélectionner un matériel',
                        'choices' =>$vehicule->getType()->getMateriels(),
                        'choice_label' => function (Materiel $materiel) {
                            return $materiel->getMarque()->getLibelle() . ' - ' . $materiel->getImmatriculation();
                        },
                    ]);
                }
                else{
                    $form->add('vehicule', EntityType::class, [
                        'class' => Materiel::class,
                        'priority'=> 2,
                        'placeholder' => 'Sélectionner un matériel',
                        'label' => 'Matériel',
                        'choices' =>[],
                        'choice_label' => function (Materiel $materiel) {
                            return $materiel->getMarque()->getLibelle() . ' - ' . $materiel->getImmatriculation();
                        },
                    ]);
                }


            }
        );
//            ->add('vehicule', EntityType::class, [
//                'class' => Materiel::class,
//
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('m')
//                        ->innerJoin('m.marque', 'marque')
//                        ->where('marque.estVehicule = :isVehicule')
//                        ->setParameter('isVehicule', true);
//                },
//                'choice_label' => function (Materiel $materiel) {
//                    return $materiel->getMarque()->getLibelle() . ' - ' . $materiel->getImmatriculation();
//                },
//                'label' => 'Matériel',
//                'placeholder' => 'Sélectionner un matériel',
//                'required' => true,
//            ])
        $builder
            ->add('typeAssurance', EntityType::class, [
                'class' => TypeAssurance::class,
                'choice_label' => 'libelle',
                'label' => 'Type d\'opération',
                'placeholder' => 'Sélectionner un type',
                'required' => true,
            ])
            ->add('montantPaye', NumberType::class, [
                'label' => 'Montant payé (FCFA)',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                'required' => false,
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
                'required' => false,
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Assurance::class,
        ]);
    }
}
