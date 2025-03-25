<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250324171048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assurance ADD date_assurance_debut DATE DEFAULT NULL, ADD date_assurance_fin DATE DEFAULT NULL, ADD date_visite_technique_debut DATE DEFAULT NULL, ADD date_visite_technique_fin DATE DEFAULT NULL, ADD date_tvmdebut DATE DEFAULT NULL, ADD date_tvmfin DATE DEFAULT NULL, DROP date_assurance, DROP date_visite_technique, DROP date_tvm');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assurance ADD date_assurance DATE DEFAULT NULL, ADD date_visite_technique DATE DEFAULT NULL, ADD date_tvm DATE DEFAULT NULL, DROP date_assurance_debut, DROP date_assurance_fin, DROP date_visite_technique_debut, DROP date_visite_technique_fin, DROP date_tvmdebut, DROP date_tvmfin');
    }
}
