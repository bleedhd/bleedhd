<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141127153214 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('CREATE TABLE Patient (id INT AUTO_INCREMENT NOT NULL, lastUpdatedDate DATETIME NOT NULL, lastUpdatedBy INT NOT NULL, isActive TINYINT(1) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, birthdate DATE NOT NULL, sex VARCHAR(10) NOT NULL, patientNumber VARCHAR(255) NOT NULL, upnNumber VARCHAR(255) NOT NULL, diagnosis VARCHAR(255) NOT NULL, diagnosisDate DATETIME NOT NULL, patientBloodType VARCHAR(10) NOT NULL, donorBloodType VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE PatientStatus (id INT AUTO_INCREMENT NOT NULL, lastUpdatedDate DATETIME NOT NULL, lastUpdatedBy INT NOT NULL, patientId INT NOT NULL, transplantDate DATE NOT NULL, transplantType VARCHAR(255) NOT NULL, transplantSource VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('DROP TABLE Patient');
        $this->addSql('DROP TABLE PatientStatus');
    }
}
