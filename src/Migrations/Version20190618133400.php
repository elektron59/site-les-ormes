<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190618133400 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mobil_home ADD auteur_id INT NOT NULL');
        $this->addSql('ALTER TABLE mobil_home ADD CONSTRAINT FK_5D4E43C460BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5D4E43C460BB6FE6 ON mobil_home (auteur_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mobil_home DROP FOREIGN KEY FK_5D4E43C460BB6FE6');
        $this->addSql('DROP INDEX IDX_5D4E43C460BB6FE6 ON mobil_home');
        $this->addSql('ALTER TABLE mobil_home DROP auteur_id');
    }
}
