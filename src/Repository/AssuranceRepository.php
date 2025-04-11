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
            ->where('a.dateAssuranceFin <= :dateSeuil OR a.dateVisiteTechniqueFin <= :dateSeuil OR a.dateTVMFin <= :dateSeuil')
            ->setParameter('dateSeuil', $dateSeuil)
            ->andWhere('a.notifEnvoyee = false')
            ->getQuery()
            ->getResult();
    }
    public function findAssurancesExpirantParTypes(int $delai,$type): array
    {
        $dateSeuil = new \DateTime();
        $dateSeuil->modify("+$delai days");

        $qb = $this->createQueryBuilder('a');
//            ->where('a.dateAssuranceFin <= :dateSeuil OR a.dateVisiteTechniqueFin <= :dateSeuil OR a.dateTVMFin <= :dateSeuil')

        if ($type == 'assurance'){
            $qb ->where('a.dateAssuranceFin <= :dateSeuil');
        }elseif ($type == 'visite_technique'){
            $qb ->where('a.dateVisiteTechniqueFin <= :dateSeuil');

        }elseif ($type == 'tvm'){
            $qb ->where('a.dateTVMFin <= :dateSeuil');

        }
        return
        $qb ->andWhere('a.notifEnvoyee = false')
            ->setParameter('dateSeuil', $dateSeuil)
            ->getQuery()
            ->getResult();

    }
}
