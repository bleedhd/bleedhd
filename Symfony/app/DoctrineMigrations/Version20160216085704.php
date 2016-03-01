<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160216085704 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Assessment ADD isDeleted TINYINT(1) NOT NULL DEFAULT 0');
        $this->addSql('ALTER TABLE Patient ADD createdDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, ADD createdBy INT NOT NULL DEFAULT -1, ADD isDeleted TINYINT(1) NOT NULL DEFAULT 0');
        $this->addSql('ALTER TABLE Patient ALTER createdDate DROP DEFAULT, ALTER createdBy DROP DEFAULT');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Assessment DROP isDeleted');
        $this->addSql('ALTER TABLE Patient DROP createdDate, DROP createdBy, DROP isDeleted');
    }
}
