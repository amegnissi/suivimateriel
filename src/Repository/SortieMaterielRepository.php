<?php

namespace App\Repository;

use App\Entity\SortieMateriel;
use App\Entity\Materiel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SortieMateriel>
 */
class SortieMaterielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SortieMateriel::class);
    }

    /**
     * Récupère les matériels actuellement sortis (dateRetour NULL)
     */
    public function findMaterielsSortis(): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.dateRetour IS NULL')
            ->orderBy('s.dateSortie', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère l'historique des sorties pour un matériel
     */
    public function findHistoriqueByMateriel(Materiel $materiel): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.materiel = :materiel')
            ->setParameter('materiel', $materiel)
            ->orderBy('s.dateSortie', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère la dernière sortie d’un matériel (en cours ou terminée)
     */
    public function findDerniereSortie(Materiel $materiel): ?SortieMateriel
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.materiel = :materiel')
            ->setParameter('materiel', $materiel)
            ->orderBy('s.dateSortie', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Vérifie si un matériel est actuellement sorti
     */
    public function isMaterielSorti(Materiel $materiel): bool
    {
        $result = $this->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->andWhere('s.materiel = :materiel')
            ->andWhere('s.dateRetour IS NULL')
            ->setParameter('materiel', $materiel)
            ->getQuery()
            ->getSingleScalarResult();

        return $result > 0;
    }
}