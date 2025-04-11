<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409094802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assurance ADD CONSTRAINT FK_386829AE35CB79E6 FOREIGN KEY (type_assurance_id) REFERENCES type_assurance (id)');
        $this->addSql('CREATE INDEX IDX_386829AE35CB79E6 ON assurance (type_assurance_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assurance DROP FOREIGN KEY FK_386829AE35CB79E6');
        $this->addSql('DROP INDEX IDX_386829AE35CB79E6 ON assurance');
    }
}
