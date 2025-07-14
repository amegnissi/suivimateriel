<?php

namespace App\Form\Courrier;

use App\Entity\Courrier\Partenaire;
use App\Entity\Courrier\TypePartenaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartenaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typePartenaire', EntityType::class, [
                'class' => TypePartenaire::class,
                'choice_label' => 'libelle',
            ])
            ->add('nomOuRaisonSocial')
            ->add('telephone')
            ->add('email')
            ->add('adresse')


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Partenaire::class,
        ]);
    }
}
