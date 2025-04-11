<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250408172459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE affectation ADD lieu_affectation_id INT DEFAULT NULL, DROP lieu_affectation');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D3A51A24E FOREIGN KEY (lieu_affectation_id) REFERENCES lieu_affectation (id)');
        $this->addSql('CREATE INDEX IDX_F4DD61D3A51A24E ON affectation (lieu_affectation_id)');
        $this->addSql('ALTER TABLE entreprise ADD sigle VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE materiel ADD code VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D3A51A24E');
        $this->addSql('DROP INDEX IDX_F4DD61D3A51A24E ON affectation');
        $this->addSql('ALTER TABLE affectation ADD lieu_affectation VARCHAR(255) DEFAULT NULL, DROP lieu_affectation_id');
        $this->addSql('ALTER TABLE entreprise DROP sigle');
        $this->addSql('ALTER TABLE materiel DROP code');
    }
}
