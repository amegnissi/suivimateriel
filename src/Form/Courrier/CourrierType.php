<?php

namespace App\Form\Courrier;

use App\Entity\Courrier\Courrier;
use App\Entity\Courrier\NatureCourrier;
use App\Entity\Courrier\Partenaire;
use App\Entity\Courrier\Statut;
use App\Entity\Courrier\TypeCourrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourrierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $typeCourrier =  $options['type_courrier'];

      $bld =   $builder
            ->add('partenaire', EntityType::class, [
                'class' => Partenaire::class,
                'choice_label' => 'NomOuRaisonSocial',
                'placeholder' => "Sélectionnez un partenaire",
                'required' => false,
            ])

//            ->add('referenceInterne',null,
//            [
//                'placeholder' => "Sélectionnez un partenaire",
//                'required' => false,
//            ])
            ->add('nature', EntityType::class, [
                'class' => NatureCourrier::class,
                'choice_label' => 'libelle',
            ])
            ->add('signataire');
      if ($typeCourrier === 'DEPART'){
          $bld
              ->add('referenceInterne',null,[
                  'label' => 'Référence',
                  'attr' => [
                      'placeholder' => "Saisissez la Référence du courrier",
                  ],

                  'required' => false,
              ])
              ->add('dateArivee', null, [
              'widget' => 'single_text',
              'label' => 'Date de départ',
          ]);
      } else {
          $bld
              ->add('referenceInterne',null,[
                  'attr' => [
                      'placeholder' => "Saisissez la référence interne du courrier",
                  ],
                  'label'=>'Référence interne du courrier',

                  'required' => false,
              ])
              ->add('reference',null,[
                  'attr' => [
                      'placeholder' => "Saisissez la référence du courrier",
                  ],
                  'label'=>'Référence du courrier',


                  'required' => false,

              ])
              ->add('dateArivee', null, [
                  'widget' => 'single_text',
                  'label' => 'Date arrivée',
                  'required'=>true
              ]);

      };

        $bld
            ->add('dateSignature', null, [
                'widget' => 'single_text',
            ])
            ->add('urlfichiercourrier', FileType::class, [
                'label' => 'Fichier (PDF file)',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '5024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        ],
                        'mimeTypesMessage' => 'Veuillez revoir le format du document joint pdf, word',
                    ])
                ],
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
