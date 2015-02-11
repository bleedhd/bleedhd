<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150108133052 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE Patient DROP patientBloodType, DROP donorBloodType, CHANGE lastUpdatedDate lastUpdatedDate DATETIME NOT NULL, CHANGE diagnosisDate diagnosisDate DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE PatientStatus CHANGE lastUpdatedDate lastUpdatedDate DATETIME NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE Patient ADD patientBloodType VARCHAR(10) DEFAULT NULL, ADD donorBloodType VARCHAR(10) DEFAULT NULL, CHANGE lastUpdatedDate lastUpdatedDate DATETIME NOT NULL, CHANGE diagnosisDate diagnosisDate DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE PatientStatus CHANGE lastUpdatedDate lastUpdatedDate DATETIME NOT NULL');
    }
}
