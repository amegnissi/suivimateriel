<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250408154647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lieu_affectation (id INT AUTO_INCREMENT NOT NULL, societe_service_id INT DEFAULT NULL, entreprise_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, type VARCHAR(10) NOT NULL, INDEX IDX_38847C07F2058D79 (societe_service_id), INDEX IDX_38847C07A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lieu_affectation ADD CONSTRAINT FK_38847C07F2058D79 FOREIGN KEY (societe_service_id) REFERENCES societe_service (id)');
        $this->addSql('ALTER TABLE lieu_affectation ADD CONSTRAINT FK_38847C07A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lieu_affectation DROP FOREIGN KEY FK_38847C07F2058D79');
        $this->addSql('ALTER TABLE lieu_affectation DROP FOREIGN KEY FK_38847C07A4AEAFEA');
        $this->addSql('DROP TABLE lieu_affectation');
    }
}
