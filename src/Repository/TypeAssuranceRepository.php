<?php

namespace App\Repository;

use App\Entity\TypeAssurance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeAssurance>
 */
class TypeAssuranceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeAssurance::class);
    }

    // Tu pourras ajouter ici des méthodes personnalisées si nécessaire
}
