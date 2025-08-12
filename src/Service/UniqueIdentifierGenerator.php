<?php

namespace App\Service;

use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\EntityManagerInterface;

class UniqueIdentifierGenerator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function generateUniqueIdentifier(string $entityClass, string $identifierColumn, string $prefix = 'Cl', int $maxAttempts = 100000): string
    {
//        $NPro = 1;
//        $v = sprintf("%s%08d", $prefix, $NPro);
//
//        while ($this->identifierExists($entityClass, $identifierColumn, $v)) {
//            $NPro++;
//            if ($NPro >= $maxAttempts) {
//                throw new \Exception("Veuillez consulter le concepteur.");
//            }
//            $v = sprintf("%s%08d", $prefix, $NPro);
//        }
//
//        return $v;
        // 1. Récupérer tous les identifiants existants avec ce préfixe
        $connection = $this->entityManager->getConnection();
        $tableName = $this->entityManager->getClassMetadata($entityClass)->getTableName();
        $identifierColumn = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $identifierColumn));

        $sql = "SELECT {$identifierColumn} FROM {$tableName} WHERE {$identifierColumn} LIKE :prefix";


        $stmt = $connection->prepare($sql);
        $stmt->bindValue('prefix', $prefix . '%', ParameterType::STRING);
        $resultSet = $stmt->executeQuery(); // ✅ DBAL 3+

        $results = $resultSet->fetchAllAssociative(); // ✅ méthode correcte en DBAL 3+

        // 2. Extraire les numéros existants
        $usedNumbers = array_map(function ($row) use ($identifierColumn, $prefix) {
            $code = $row[$identifierColumn];
            return (int)substr($code, strlen($prefix));
        }, $results);

        $usedNumbers = array_flip($usedNumbers);

        // 3. Retourner le 1er identifiant libre
        for ($i = 1; $i <= $maxAttempts; $i++) {
            if (!isset($usedNumbers[$i])) {
                return sprintf("%s%08d", $prefix, $i);
            }
        }

        throw new \Exception("Aucun identifiant disponible. Veuillez consulter le concepteur.");
    }


    public function generateUniqueIdentifierBoucle(
        string $entityClass,
        string $identifierColumn,
        string $prefix = 'Cl',
        array $excluded = [],
        int $maxAttempts = 10000000,

    ): string {
        $connection = $this->entityManager->getConnection();
        $tableName = $this->entityManager->getClassMetadata($entityClass)->getTableName();

        $identifierColumn = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $identifierColumn));


        $sql = "SELECT {$identifierColumn} FROM {$tableName} WHERE {$identifierColumn} LIKE :prefix";
        $stmt = $connection->prepare($sql);
        $stmt->bindValue('prefix', $prefix . '%');
        $resultSet = $stmt->executeQuery();
        $results = $resultSet->fetchAllAssociative();

        $used = array_map(fn($row) => (int)substr($row[$identifierColumn], strlen($prefix)), $results);
        $used = array_merge($used, $excluded); // ← ajoute ceux déjà générés
        $used = array_flip($used);

        for ($i = 1; $i <= $maxAttempts; $i++) {
            if (!isset($used[$i])) {
                return sprintf('%s%08d', $prefix, $i);
            }
        }

        throw new \Exception("Aucun identifiant disponible.");
    }

}
