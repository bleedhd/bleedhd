<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150113082942 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Response MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE Response DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE Response DROP id');
        $this->addSql('ALTER TABLE Response ADD PRIMARY KEY (assessmentId, questionSlug)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Response DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE Response ADD id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE Response ADD PRIMARY KEY (id)');
    }
}
