<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250405130603 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE type_maintenance (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE maintenance ADD type_maintenance_id INT DEFAULT NULL, DROP type_maintenance');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E9A0012B28 FOREIGN KEY (type_maintenance_id) REFERENCES type_maintenance (id)');
        $this->addSql('CREATE INDEX IDX_2F84F8E9A0012B28 ON maintenance (type_maintenance_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E9A0012B28');
        $this->addSql('DROP TABLE type_maintenance');
        $this->addSql('DROP INDEX IDX_2F84F8E9A0012B28 ON maintenance');
        $this->addSql('ALTER TABLE maintenance ADD type_maintenance VARCHAR(255) NOT NULL, DROP type_maintenance_id');
    }
}
