<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150421081217 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('UPDATE Assessment SET questionnaire=\'gvhd-late-onset-acute\' WHERE questionnaire=\'gvhd-delayed-acute\'');
        $this->addSql('UPDATE Response SET questionSlug=CONCAT(\'gvhd-late-onset-acute\', SUBSTRING(questionSlug, 19)) WHERE questionSlug LIKE \'gvhd-delayed-acute%\'');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('UPDATE Assessment SET questionnaire=\'gvhd-delayed-acute\' WHERE questionnaire=\'gvhd-late-onset-acute\'');
        $this->addSql('UPDATE Response SET questionSlug=CONCAT(\'gvhd-delayed-acute\', SUBSTRING(questionSlug, 22)) WHERE questionSlug LIKE \'gvhd-late-onset-acute%\'');
    }
}
