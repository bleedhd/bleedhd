<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141205110957 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Patient CHANGE upnNumber upn VARCHAR(255) DEFAULT NULL, CHANGE lastUpdatedDate lastUpdatedDate DATETIME NOT NULL, CHANGE sex sex VARCHAR(10) DEFAULT NULL, CHANGE patientNumber patientNumber VARCHAR(255) DEFAULT NULL, CHANGE diagnosis diagnosis VARCHAR(255) DEFAULT NULL, CHANGE diagnosisDate diagnosisDate DATETIME DEFAULT NULL, CHANGE patientBloodType patientBloodType VARCHAR(10) DEFAULT NULL, CHANGE donorBloodType donorBloodType VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE PatientStatus CHANGE lastUpdatedDate lastUpdatedDate DATETIME NOT NULL, CHANGE transplantDate transplantDate DATE DEFAULT NULL, CHANGE transplantType transplantType VARCHAR(255) DEFAULT NULL, CHANGE transplantSource transplantSource VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE PatientStatus ADD CONSTRAINT FK_D0DC1C8C8F803478 FOREIGN KEY (patientId) REFERENCES Patient (id)');
        $this->addSql('CREATE INDEX IDX_D0DC1C8C8F803478 ON PatientStatus (patientId)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Patient CHANGE upn upnNumber VARCHAR(255) NOT NULL, CHANGE lastUpdatedDate lastUpdatedDate DATETIME NOT NULL, CHANGE sex sex VARCHAR(10) NOT NULL, CHANGE patientNumber patientNumber VARCHAR(255) NOT NULL, CHANGE diagnosis diagnosis VARCHAR(255) NOT NULL, CHANGE diagnosisDate diagnosisDate DATETIME NOT NULL, CHANGE patientBloodType patientBloodType VARCHAR(10) NOT NULL, CHANGE donorBloodType donorBloodType VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE PatientStatus DROP FOREIGN KEY FK_D0DC1C8C8F803478');
        $this->addSql('DROP INDEX IDX_D0DC1C8C8F803478 ON PatientStatus');
        $this->addSql('ALTER TABLE PatientStatus CHANGE lastUpdatedDate lastUpdatedDate DATETIME NOT NULL, CHANGE transplantDate transplantDate DATE NOT NULL, CHANGE transplantType transplantType VARCHAR(255) NOT NULL, CHANGE transplantSource transplantSource VARCHAR(255) NOT NULL');
    }
}
