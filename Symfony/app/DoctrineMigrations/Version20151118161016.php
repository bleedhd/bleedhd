<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151118161016 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('UPDATE Assessment SET questionnaire=\'gvhd-new-diagnosis\' WHERE questionnaire=\'gvhd-first-diagnosis\'');
        $this->addSql('UPDATE Response SET questionSlug=CONCAT(\'gvhd-new-diagnosis\', SUBSTRING(questionSlug, 21)) WHERE questionSlug LIKE \'gvhd-first-diagnosis%\'');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('UPDATE Assessment SET questionnaire=\'gvhd-first-diagnosis\' WHERE questionnaire=\'gvhd-new-diagnosis\'');
        $this->addSql('UPDATE Response SET questionSlug=CONCAT(\'gvhd-first-diagnosis\', SUBSTRING(questionSlug, 19)) WHERE questionSlug LIKE \'gvhd-new-diagnosis%\'');
    }
}
