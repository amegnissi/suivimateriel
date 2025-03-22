<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250321143716 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employe CHANGE telephone_personnel telephone_personnel VARCHAR(255) DEFAULT NULL, CHANGE copie_carte_id copie_carte_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E96D0ABA22');
        $this->addSql('DROP INDEX IDX_2F84F8E96D0ABA22 ON maintenance');
        $this->addSql('ALTER TABLE maintenance ADD materiel_id INT NOT NULL, ADD statut TINYINT(1) NOT NULL, DROP affectation_id, CHANGE type_mainteance type_maintenance VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E916880AAF FOREIGN KEY (materiel_id) REFERENCES materiel (id)');
        $this->addSql('CREATE INDEX IDX_2F84F8E916880AAF ON maintenance (materiel_id)');
        $this->addSql('ALTER TABLE materiel CHANGE libelle libelle VARCHAR(255) DEFAULT NULL, CHANGE numero_serie numero_serie VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employe CHANGE telephone_personnel telephone_personnel VARCHAR(255) NOT NULL, CHANGE copie_carte_id copie_carte_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E916880AAF');
        $this->addSql('DROP INDEX IDX_2F84F8E916880AAF ON maintenance');
        $this->addSql('ALTER TABLE maintenance ADD affectation_id INT DEFAULT NULL, DROP materiel_id, DROP statut, CHANGE type_maintenance type_mainteance VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E96D0ABA22 FOREIGN KEY (affectation_id) REFERENCES affectation (id)');
        $this->addSql('CREATE INDEX IDX_2F84F8E96D0ABA22 ON maintenance (affectation_id)');
        $this->addSql('ALTER TABLE materiel CHANGE libelle libelle VARCHAR(255) NOT NULL, CHANGE numero_serie numero_serie VARCHAR(255) NOT NULL');
    }
}
