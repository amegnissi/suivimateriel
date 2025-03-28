<?php

namespace App\Repository;

use App\Entity\Assurance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AssuranceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Assurance::class);
    }

    /**
     * Récupère les assurances dont la date d'expiration approche selon le délai défini par l'entreprise.
     */
    public function findAssurancesExpirant(int $delai): array
    {
        $dateSeuil = new \DateTime();
        $dateSeuil->modify("+$delai days");

        return $this->createQueryBuilder('a')
            ->where('a.dateAssurance <= :dateSeuil OR a.dateVisiteTechnique <= :dateSeuil OR a.dateTVM <= :dateSeuil')
            ->setParameter('dateSeuil', $dateSeuil)
            ->andWhere('a.notifEnvoyee = false')
            ->getQuery()
            ->getResult();
    }
}
