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
            ->where('a.dateFin <= :dateSeuil')
            ->setParameter('dateSeuil', $dateSeuil)
            ->andWhere('a.notifEnvoyee = false')
            ->getQuery()
            ->getResult();
    }

    public function findAssurancesExpirantParTypes(int $delai=null,$type=null): array
    {
        $dateSeuil = new \DateTime();
        $dateSeuil->modify("+$delai days");

        $qb = $this->createQueryBuilder('a')
            ->select('a as op','COUNT(a) as total', 'type.libelle as typeoperation','type.id as idtype','a.id as id')
            ->leftJoin('a.typeAssurance','type')
            ->groupBy('a.typeAssurance')
            ->where('a.dateFin <= :dateSeuil')
            ->andWhere('a.estRenouvelle = false')
            ->andWhere('a.notifEnvoyee = false');
        if ($type){
            $qb->andWhere('a.typeAssurance = :type')
                ->setParameter('type', $type);
        }


        return
        $qb
            ->setParameter('dateSeuil', new \DateTime('now'))
            ->getQuery()
            ->getResult();

    }

    public function assuranceParTypeMateriel($type,$expire=false): array
    {

        $qb = $this->createQueryBuilder('a')
//            ->leftJoin('a.typeAssurance','type')
            ->leftJoin('a.vehicule','v')
            ->leftJoin('v.type','t')
            ->where('t.id = :type')
            ->andWhere('t.id = :type')
            ->andWhere('a.estRenouvelle = :ex')
            
            ;



        return
            $qb
                ->setParameter('type', $type)
                 ->setParameter('ex',$expire)
                ->getQuery()
                ->getResult();

    }
}
