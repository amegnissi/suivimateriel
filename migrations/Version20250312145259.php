<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250312145259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employe ADD entreprise_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B9A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('CREATE INDEX IDX_F804D3B9A4AEAFEA ON employe (entreprise_id)');
        $this->addSql('ALTER TABLE materiel ADD entreprise_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B091A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('CREATE INDEX IDX_18D2B091A4AEAFEA ON materiel (entreprise_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B9A4AEAFEA');
        $this->addSql('DROP INDEX IDX_F804D3B9A4AEAFEA ON employe');
        $this->addSql('ALTER TABLE employe DROP entreprise_id');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B091A4AEAFEA');
        $this->addSql('DROP INDEX IDX_18D2B091A4AEAFEA ON materiel');
        $this->addSql('ALTER TABLE materiel DROP entreprise_id');
    }
}
