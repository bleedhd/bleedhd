<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150108152328 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Assessment (id INT AUTO_INCREMENT NOT NULL, lastUpdatedDate DATETIME NOT NULL, lastUpdatedBy INT NOT NULL, patientId INT NOT NULL, questionnaire VARCHAR(255) NOT NULL, startDate DATE DEFAULT NULL, startTime TIME DEFAULT NULL, platelets DOUBLE PRECISION DEFAULT NULL, remarks LONGTEXT DEFAULT NULL, INDEX IDX_B80F3EA08F803478 (patientId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Response (id INT AUTO_INCREMENT NOT NULL, lastUpdatedDate DATETIME NOT NULL, lastUpdatedBy INT NOT NULL, assessmentId INT NOT NULL, questionSlug VARCHAR(255) NOT NULL, result LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', INDEX IDX_C70D69AD5DEA8147 (assessmentId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Assessment ADD CONSTRAINT FK_B80F3EA08F803478 FOREIGN KEY (patientId) REFERENCES Patient (id)');
        $this->addSql('ALTER TABLE Response ADD CONSTRAINT FK_C70D69AD5DEA8147 FOREIGN KEY (assessmentId) REFERENCES Assessment (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Response DROP FOREIGN KEY FK_C70D69AD5DEA8147');
        $this->addSql('DROP TABLE Assessment');
        $this->addSql('DROP TABLE Response');
    }
}
