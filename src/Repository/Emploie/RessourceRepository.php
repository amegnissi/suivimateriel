<?php

namespace App\Repository\Emploie;

use App\Entity\Emploie\Ressource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ressource>
 */
class RessourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ressource::class);
    }

    //    /**
    //     * @return Ressource[] Returns an array of Ressource objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Ressource
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function getTotalMontantPris($periode): ?float
    {
        return $this->createQueryBuilder('r')
            ->select('SUM(r.montantPris) as total')
            ->where('r.periode = :periode')
            ->setParameter('periode', $periode)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getRessourcePeriode($periode,$mois, $annee) {
        return $this->createQueryBuilder('r')
            ->where('r.periode = :periode')
            ->andWhere('r.mois = :mois')
            ->andWhere('r.annee = :annee')
            ->setParameter('mois', $mois)
            ->setParameter('annee', $annee)
            ->setParameter('periode', $periode)
            ->getQuery()
            ->getResult()
            ;
    }
}
