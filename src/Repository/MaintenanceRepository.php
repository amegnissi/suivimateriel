<?php

namespace App\Repository;

use App\Entity\Maintenance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Maintenance>
 */
class MaintenanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Maintenance::class);
    }

    public function countByMonth(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT YEAR(date_intervention) as annee, 
                   MONTH(date_intervention) as mois, 
                   COUNT(id) as total 
            FROM maintenance
            GROUP BY annee, mois
            ORDER BY annee ASC, mois ASC
        ";

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        return $resultSet->fetchAllAssociative();
    }

    //    /**
    //     * @return Maintenance[] Returns an array of Maintenance objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Maintenance
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function getCoutMaintenanceParMois(): array
    {
        return $this->createQueryBuilder('m')
            ->select('YEAR(m.dateIntervention) AS annee', 'MONTH(m.dateIntervention) AS mois', 'SUM(m.cout) AS totalCout')
            ->where('m.dateIntervention IS NOT NULL')
            ->groupBy('annee', 'mois')
            ->orderBy('annee', 'ASC')
            ->addOrderBy('mois', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getCoutMaintenanceParMois2(\DateTimeInterface $startDate, \DateTimeInterface $endDate,
                                               $periode)
    {
        $queryBuilder = $this->createQueryBuilder('m')
            ->select('SUM(l.total) AS total')
            ->where('m.dateIntervention IS NOT NULL');

        $queryBuilder
                    ->addSelect('YEAR(m.dateIntervention) as year')
                    ->addSelect('MONTH(m.dateIntervention) as month')
                    ->groupBy('year')
                    ->addGroupBy('month')
                    ->andWhere($queryBuilder->expr()->orX(
                        'YEAR(m.dateIntervention) = :startYear AND YEAR(m.dateIntervention) = :endYear AND MONTH(m.dateIntervention) >= :startMonth AND MONTH(m.dateIntervention) <= :endMonth',
                        'YEAR(m.dateIntervention) = :startYear AND YEAR(m.dateIntervention) != :endYear AND MONTH(m.dateIntervention) >= :startMonth',
                        'YEAR(m.dateIntervention) = :endYear AND YEAR(m.dateIntervention) != :startYear AND MONTH(m.dateIntervention) <= :endMonth',
                        'YEAR(m.dateIntervention) > :startYear AND YEAR(m.dateIntervention) < :endYear',
                    ))
                    ->setParameter('startYear', $startDate->format('Y'))
                    ->setParameter('startMonth', $startDate->format('n'))
                    ->setParameter('endYear', $endDate->format('Y'))
                    ->setParameter('endMonth', $endDate->format('n'));
                $dateFormatter = static function (\DateTimeInterface $date): string {
                    return $date->format('n.Y');
                };
                $resultFormatter = static function (array $data): string {
                    return $data['month'] . '/' . $data['year'];
                };

        return $queryBuilder->getQuery()->getArrayResult();
    }

}
