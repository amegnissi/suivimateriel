<?php

namespace App\Form;

use App\Entity\Marque;
use App\Entity\Materiel;
use App\Entity\TypeMateriel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MaterielType extends AbstractType
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', EntityType::class, [
                'class' => TypeMateriel::class,
                'required' => false,
                'choice_label' => 'libelle',
                'label' => 'Type de matériel',
                'placeholder' => 'Choisissez un type',
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
            ->add('numeroSerie', TextType::class, [
                'label' => 'Numéro de série',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('libelle', TextareaType::class, [
                'required' => false,
                'label' => 'Description',
                'attr' => ['class' => 'form-control'],
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

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit']);
    }
        public
        function onPreSetData(FormEvent $event): void
        {
            $materiel = $event->getData();
            $form = $event->getForm();

            if ($materiel && $materiel->getType()) {
                $this->addConditionalFields($form, $materiel->getType());
            }
        }

        public
        function onPreSubmit(FormEvent $event): void
        {
            $data = $event->getData();
            $form = $event->getForm();

            // Récupérer le TypeBol depuis l'ID soumis
            if (isset($data['type']) && $data['type']) {
                $typeBolId = $data['type'];

                // Récupérer l'entité TypeBol depuis la base de données
                $type = $this->entityManager
                    ->getRepository(TypeMateriel::class)
                    ->find($typeBolId);
//                dump($typeBolId,$form->getConfig()->getOption('em'));
                dump($type);
                if ($type) {
                    $this->addConditionalFields($form, $type);
                }
            }
        }

        private
        function addConditionalFields(FormInterface $form, TypeMateriel $type): void
        {
            $typeLibelle = $type->getLibelle();
dump($typeLibelle);
            switch ($typeLibelle) {
                case 'Véhicule':
                    $form
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
                    ;
                    break;
            }

        }

        public
        function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                'data_class' => Materiel::class,
                'em' => null,
            ]);
        }
    }
