<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250404181307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employe ADD contact_urgence_nom VARCHAR(255) DEFAULT NULL, ADD contact_urgence_prenom VARCHAR(255) DEFAULT NULL, ADD contact_urgence_telephone VARCHAR(20) DEFAULT NULL, ADD contact_urgence_adresse VARCHAR(255) DEFAULT NULL, ADD contact_urgence_lien VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employe DROP contact_urgence_nom, DROP contact_urgence_prenom, DROP contact_urgence_telephone, DROP contact_urgence_adresse, DROP contact_urgence_lien');
    }
}
