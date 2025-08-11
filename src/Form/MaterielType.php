<?php

namespace App\Form;

use App\Entity\Marque;
use App\Entity\Materiel;
use App\Entity\TypeMateriel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('name', TextType::class, [
                'label' => 'Nom du materiel',
                'attr' => ['class' => 'form-control',
                    'placeholder'=> 'Nom du materiel ex',
                    ],
            ])
            ->add('coutAcquisition', TextType::class, [
                'label' => 'Cout d\'acquisition du materiel',
                'attr' => ['class' => 'form-control',
                    'placeholder'=> 'Cout d\'acquisition du materiel',
                ],
            ])
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
            ->add('statutMateriel', ChoiceType::class, [
                'label' => 'Etat du materiel',
                'choices' => [
                    'Neuf' => 'NEUF',
                    'Bon' => 'BON',
                    'Moyen' => 'MOYEN',
                    'Défaillant' => 'DEFAILLANT',
                    'Hors service' => 'HORS SERVICE',
                ],
                'attr' => ['class' => 'form-control'],
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
            ->add('puissance', TextType::class, [
                'label' => 'Puissance',
                'required' => false,
                'attr' => ['class' => 'form-control',
                    'placeholder'=> 'puissance du moteur en chevaux ou kW',
                    ],
            ])
            ->add('capacite', TextType::class, [
                'label' => 'Capacite',
                'required' => false,
                'attr' => ['class' => 'form-control',
                    'placeholder'=> "en tonnes, m³, etc si c'est un camion",
                ],
            ])
            ->add('statutMateriel', ChoiceType::class, [
                'label' => 'Etat du materiel',
                'required' => false,
                'choices' => [
                    'Bon' => 'BON',
                    'Moyen' => 'MOYEN',
                    'Défaillant' => 'DEFAILLANT',
                    'Hors service' => 'HORS SERVICE',
                ],
                'attr' => ['class' => 'form-control',
                    'placeholder'=> "en tonnes, m³, etc si c'est un camion",
                ],
            ])
            ->add('typeMoteur', ChoiceType::class, [
                'label' => 'Type de Moteur',
                'required' => false,
                'choices' => [
                    'diesel' => 'diesel',
                    'Essence' => 'ESSENCE',
                    'Electrique' => 'ELECTRIQUE',
                    'Hybride' => 'HYBRIDE',
                ],
                'attr' => ['class' => 'form-control',
                    'placeholder'=> "en tonnes, m³, etc si c'est un camion",
                ],
            ])
        ->add('processeur', ChoiceType::class, [
            'label' => 'Type de processeur',
            'required' => false,
            'choices' => [
                'Intel' => 'INTEL',
                'Intel Core I3' => 'CORE I3',
                'Intel Core I5' => 'CORE I5',
                'Intel Core I7' => 'CORE I7',
                'AMD' => 'AMD',
                'Apple M1' => 'M1',
                'Apple M2' => 'M2',
                'Apple M3' => 'M3',

            ],
            'attr' => ['class' => 'form-control',
            ],
        ])
            ->add('disqueDur', ChoiceType::class, [
                'label' => 'Type de de disque dur',
                'required' => false,
                'choices' => [
                    'Sata' => 'SATA',
                    'HDD' => 'HDD',
                    'SSD' => 'SSD',
                    'NVMe' => 'NVMe',

                ],
                'attr' => ['class' => 'form-control',
                ],
            ])
            ->add('capaciteDisqueDur', TextType::class, [
                'label' => 'Capacité du disque du dur',
                'required' => false,
                'attr' => ['class' => 'form-control',
                ],
            ])
            ->add('OS', ChoiceType::class, [
                'label' => 'Systeme d\'exploitation',
                'required' => false,
                'choices' => [
                    'Windows 7' => 'WINDOWS 7',
                    'Windows 8' => 'WINDOWS 8',
                    'Windows 10' => 'WINDOWS 10',
                    'Windows 11' => 'WINDOWS 11',
                    'LINUX' => 'LINUX',
                    'MACOS' => 'MACOS',

                ],
                'attr' => ['class' => 'form-control',

                ],
            ])
            ->add('frequence', IntegerType::class, [
                'label' => 'Frequence du processeur (GHz)',
                'required' => false,
                'attr' => ['class' => 'form-control'
                ],
            ])
            ->add('memoireRAM', TextType::class, [
                'label' => 'Memoire RAM',
                'required' => false,

                'attr' => ['class' => 'form-control',

                ],
            ])


            ->add('typeImprimante', ChoiceType::class, [
                'label' => 'Type d\'imprimante',

    'required' => false,
                'choices' => [
                    'Laser' => 'Laser',
                    'Jet d\’encre' => 'JetEncre',
                    'Multifonction' => 'Multifonction',
                    'Matricielle' => 'Matricielle',
                    'autres' => 'autres',

                ],
                'attr' => ['class' => 'form-control',
                ],
            ])
            ->add('vitesseImpression', TextType::class, [
                'label' => 'Vitesse d\'impression',
                'required' => false,
                'attr' => ['class' => 'form-control'
                ],
            ])
            ->add('PPM', TextType::class, [
                'label' => 'Pages par minute',
                'required' => false,
                'attr' => ['class' => 'form-control',
                ],
            ])
            ->add('resolutionImpression', TextType::class, [
                'label' => 'Resolution d\'Impression  (dots per inch)',
                'required' => false,
                'attr' => ['class' => 'form-control'
                ],
            ])
            ->add('connectivite', ChoiceType::class, [
                'label' => 'connectivite',
                'required' => false,
                'choices' => [
                    'USB' => 'USB',
                    'Ethernet' => 'Ethernet',
                    'Bluetooth' => 'Bluetooth',
                ],
                'attr' => ['class' => 'form-control',
                    'placeholder'=> "en tonnes, m³, etc si c'est un camion",
                ],
            ])
        ;
//
//        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
//        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit']);
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
