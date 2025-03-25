<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250324144121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE assurance (id INT AUTO_INCREMENT NOT NULL, vehicule_id INT NOT NULL, date_assurance DATE DEFAULT NULL, date_visite_technique DATE DEFAULT NULL, date_tvm DATE DEFAULT NULL, notif_envoyee TINYINT(1) NOT NULL, INDEX IDX_386829AE4A4A3511 (vehicule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, message VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, vue TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assurance ADD CONSTRAINT FK_386829AE4A4A3511 FOREIGN KEY (vehicule_id) REFERENCES materiel (id)');
        $this->addSql('ALTER TABLE entreprise ADD delai_assurance INT DEFAULT NULL, ADD delai_tvm INT DEFAULT NULL, ADD delai_visite_technique INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assurance DROP FOREIGN KEY FK_386829AE4A4A3511');
        $this->addSql('DROP TABLE assurance');
        $this->addSql('DROP TABLE notification');
        $this->addSql('ALTER TABLE entreprise DROP delai_assurance, DROP delai_tvm, DROP delai_visite_technique');
    }
}
