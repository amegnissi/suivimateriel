<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250418085129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sortie_materiel (id INT AUTO_INCREMENT NOT NULL, materiel_id INT NOT NULL, employe_id INT DEFAULT NULL, date_sortie DATE NOT NULL, date_retour DATE DEFAULT NULL, motif VARCHAR(255) DEFAULT NULL, INDEX IDX_2B137D7F16880AAF (materiel_id), INDEX IDX_2B137D7F1B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sortie_materiel ADD CONSTRAINT FK_2B137D7F16880AAF FOREIGN KEY (materiel_id) REFERENCES materiel (id)');
        $this->addSql('ALTER TABLE sortie_materiel ADD CONSTRAINT FK_2B137D7F1B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE materiel ADD est_sorti TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sortie_materiel DROP FOREIGN KEY FK_2B137D7F16880AAF');
        $this->addSql('ALTER TABLE sortie_materiel DROP FOREIGN KEY FK_2B137D7F1B65292');
        $this->addSql('DROP TABLE sortie_materiel');
        $this->addSql('ALTER TABLE materiel DROP est_sorti');
    }
}
