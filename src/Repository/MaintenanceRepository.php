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
}
