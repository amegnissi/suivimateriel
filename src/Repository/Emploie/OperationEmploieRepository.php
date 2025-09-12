<?php

namespace App\Repository\Emploie;

use App\Entity\Emploie\OperationEmploie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OperationEmploie>
 */
class OperationEmploieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OperationEmploie::class);
    }

    //    /**
    //     * @return OperationEmploie[] Returns an array of OperationEmploie objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?OperationEmploie
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * Calcule la somme totale des montants à payer
     *
     * @return float|null
     */
    public function getTotalMontantAPayer($periode): ?float
    {
        return $this->createQueryBuilder('o')
            ->select('SUM(o.montantAPayer) as total')
            ->where('o.periode = :periode')
            ->setParameter('periode', $periode)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Retourne un tableau contenant les totaux des montants à payer, des retenues et la différence
     *
     * @return array{
     *     totalMontantAPayer: float,
     *     totalRetenues: float,
     *     difference: float
     * }|null
     */
    public function getTotalRetenue($periode,$mois=null,$annee=null): array
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->select([
                'COALESCE(SUM(o.montantAPayer), 0) as totalMontantAPayer',
                'COALESCE(SUM(o.retenue), 0) as totalRetenues'
            ])
            ->where('o.periode = :periode')
            ->setParameter('periode', $periode)
        ;

        if($mois && $annee){
            $queryBuilder

                ->andWhere('o.mois = :mois')
                ->andWhere('o.annee = :annee')
                ->setParameter('mois', $mois)

                ->setParameter('annee', $annee);
        }

        $result = $queryBuilder->getQuery()->getSingleResult();

        return [
            'totalMontantAPayer' => (float)$result['totalMontantAPayer'],
            'totalRetenues' => (float)$result['totalRetenues'],
            'difference' => (float)$result['totalMontantAPayer'] - (float)$result['totalRetenues']
        ];
    }

    public function demandeAutorisations(){
        return $this->createQueryBuilder('o')
            ->andWhere('o.autorisation IS NOT NULL')
            ->getQuery()
            ->getResult();
    }

    public function getOperationEmploiePeriode($periode,$mois, $annee) {
        return $this->createQueryBuilder('o')
            ->where('o.periode = :periode')
            ->andWhere('o.mois = :mois')
            ->andWhere('o.annee = :annee')
            ->setParameter('mois', $mois)
            ->setParameter('annee', $annee)
            ->setParameter('periode', $periode)
            ->getQuery()
            ->getResult()
            ;
    }
}
