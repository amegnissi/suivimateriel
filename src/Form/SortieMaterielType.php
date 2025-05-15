<?php
namespace App\Form;

use App\Entity\Materiel;
use App\Entity\SortieMateriel;
use App\Repository\MaterielRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieMaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('materiel', EntityType::class, [
                'class' => Materiel::class,
                'label' => 'Matériel à sortir',
                'placeholder' => 'Sélectionnez un matériel',
                'query_builder' => function (MaterielRepository $repo) {
                    return $repo->createQueryBuilder('m')
                        ->where('m.estSorti = false');
                },
                'choice_label' => function (Materiel $materiel) {
                    return $materiel->getMarque()->getLibelle(). ' - ' . $materiel->getImmatriculation();
                },
                'attr' => ['class' => 'form-control'],
            ])
            ->add('motif', TextareaType::class, [
                'label' => 'Motif de la sortie',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 3],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SortieMateriel::class,
            'entreprise' => null, // paramètre personnalisé requis
        ]);
    }
}
