<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190523054701 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mobil_home ADD tarif_jour_hs_mh DOUBLE PRECISION NOT NULL, ADD tarif_semaine_hs_mh DOUBLE PRECISION NOT NULL, ADD tarif_jour_bs_mh DOUBLE PRECISION NOT NULL, ADD tarif_semaine_bs_mh DOUBLE PRECISION NOT NULL, ADD detail_mh LONGTEXT NOT NULL, DROP tarif_jour_mh, DROP remise_semaine_mh');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mobil_home ADD tarif_jour_mh DOUBLE PRECISION NOT NULL, ADD remise_semaine_mh DOUBLE PRECISION NOT NULL, DROP tarif_jour_hs_mh, DROP tarif_semaine_hs_mh, DROP tarif_jour_bs_mh, DROP tarif_semaine_bs_mh, DROP detail_mh');
    }
}
