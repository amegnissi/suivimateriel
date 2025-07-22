<?php

namespace App\Repository\Courrier;

use App\Data\RechercheData;
use App\Entity\Courrier\Courrier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Courrier>
 */
class CourrierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Courrier::class);
    }

//    /**
//     * @return Courrier[] Returns an array of Courrier objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Courrier
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
       * @return Courrier[] Returns an array of Courrier objects
     * */
    public function recherche(RechercheData $search) : array
    {
        $query = $this->createQueryBuilder('c')
            ->innerJoin('c.partenaire', 'p')
            ->innerJoin('c.nature', 'n');
        if (!empty($search->getObjet())) {
            $query = $query
                ->andWhere('c.objet LIKE :objet')
                ->setParameter('objet', '%' . $search->getObjet() . '%');
        };
        if (!empty($search->getReference())) {
            $query = $query
                ->andWhere('c.referenceInterne LIKE :reference')
                ->setParameter('reference', '%' . $search->getReference() . '%');
        };
        if (!empty($search->getPartenaire())) {
            $query = $query
                ->andWhere('c.partenaire = :partenaire')
                ->setParameter('partenaire', $search->getPartenaire());
        };

        if (!empty($search->getNature())) {
            $query = $query
                ->andWhere('c.nature = :nature')
                ->setParameter('nature', $search->getNature());
        };

        if (!empty($search->getDateDebut())) {

            $query = $query->andWhere('c.dateArivee >= :dateDebut')
                ->setParameter('dateDebut', $search->getDateDebut());
        }

        if (!empty($search->getDateFin())) {

            $query = $query->andWhere('c.dateArivee <= :dateFin')
                ->setParameter('dateFin', $search->getDateFin());
        }


        return $query->getQuery()
            //   ->getSQL()
            ->getResult();
    }
}
