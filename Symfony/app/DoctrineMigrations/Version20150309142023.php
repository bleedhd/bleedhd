<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150309142023 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('UPDATE Assessment SET questionnaire=\'gvhd-organ-scoring\' WHERE questionnaire=\'gvhd-current-staging\'');
        $this->addSql('UPDATE Response SET questionSlug=CONCAT(\'gvhd-organ-scoring\', SUBSTRING(questionSlug, 21)) WHERE questionSlug LIKE \'gvhd-current-staging%\'');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('UPDATE Assessment SET questionnaire=\'gvhd-current-staging\' WHERE questionnaire=\'gvhd-organ-scoring\'');
        $this->addSql('UPDATE Response SET questionSlug=CONCAT(\'gvhd-current-staging\', SUBSTRING(questionSlug, 19)) WHERE questionSlug LIKE \'gvhd-organ-scoring%\'');
    }
}
