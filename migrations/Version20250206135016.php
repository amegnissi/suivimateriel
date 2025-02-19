<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250206135016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE depart_employe (id INT AUTO_INCREMENT NOT NULL, employe_id INT DEFAULT NULL, date_depart DATE DEFAULT NULL, motif LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_549BF2651B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employe (id INT AUTO_INCREMENT NOT NULL, poste_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) DEFAULT NULL, telephone_personnel VARCHAR(255) NOT NULL, telephone_corporate VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, copie_carte_id VARCHAR(255) NOT NULL, copie_diplome VARCHAR(255) DEFAULT NULL, certificat_acquite_visuel VARCHAR(255) DEFAULT NULL, depart TINYINT(1) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, INDEX IDX_F804D3B9A0905086 (poste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, ifu VARCHAR(255) DEFAULT NULL, site_web VARCHAR(255) DEFAULT NULL, gerant VARCHAR(255) DEFAULT NULL, kilometrage DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poste (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE depart_employe ADD CONSTRAINT FK_549BF2651B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B9A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depart_employe DROP FOREIGN KEY FK_549BF2651B65292');
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B9A0905086');
        $this->addSql('DROP TABLE depart_employe');
        $this->addSql('DROP TABLE employe');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE poste');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
