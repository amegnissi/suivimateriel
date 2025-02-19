<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212163932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE affectation ADD materiel_id INT DEFAULT NULL, ADD societe_id INT DEFAULT NULL, ADD employe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D316880AAF FOREIGN KEY (materiel_id) REFERENCES materiel (id)');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D3FCF77503 FOREIGN KEY (societe_id) REFERENCES societe_service (id)');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D31B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('CREATE INDEX IDX_F4DD61D316880AAF ON affectation (materiel_id)');
        $this->addSql('CREATE INDEX IDX_F4DD61D3FCF77503 ON affectation (societe_id)');
        $this->addSql('CREATE INDEX IDX_F4DD61D31B65292 ON affectation (employe_id)');
        $this->addSql('ALTER TABLE maintenance ADD affectation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E96D0ABA22 FOREIGN KEY (affectation_id) REFERENCES affectation (id)');
        $this->addSql('CREATE INDEX IDX_2F84F8E96D0ABA22 ON maintenance (affectation_id)');
        $this->addSql('ALTER TABLE materiel ADD type_id INT DEFAULT NULL, ADD marque_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B091C54C8C93 FOREIGN KEY (type_id) REFERENCES type_materiel (id)');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B0914827B9B2 FOREIGN KEY (marque_id) REFERENCES marque (id)');
        $this->addSql('CREATE INDEX IDX_18D2B091C54C8C93 ON materiel (type_id)');
        $this->addSql('CREATE INDEX IDX_18D2B0914827B9B2 ON materiel (marque_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D316880AAF');
        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D3FCF77503');
        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D31B65292');
        $this->addSql('DROP INDEX IDX_F4DD61D316880AAF ON affectation');
        $this->addSql('DROP INDEX IDX_F4DD61D3FCF77503 ON affectation');
        $this->addSql('DROP INDEX IDX_F4DD61D31B65292 ON affectation');
        $this->addSql('ALTER TABLE affectation DROP materiel_id, DROP societe_id, DROP employe_id');
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E96D0ABA22');
        $this->addSql('DROP INDEX IDX_2F84F8E96D0ABA22 ON maintenance');
        $this->addSql('ALTER TABLE maintenance DROP affectation_id');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B091C54C8C93');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B0914827B9B2');
        $this->addSql('DROP INDEX IDX_18D2B091C54C8C93 ON materiel');
        $this->addSql('DROP INDEX IDX_18D2B0914827B9B2 ON materiel');
        $this->addSql('ALTER TABLE materiel DROP type_id, DROP marque_id');
    }
}
