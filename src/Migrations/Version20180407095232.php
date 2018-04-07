<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180407095232 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE round_result ADD black_participant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE round_result ADD CONSTRAINT FK_D7DDF8BFBC902D37 FOREIGN KEY (black_participant_id) REFERENCES participant (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_D7DDF8BFBC902D37 ON round_result (black_participant_id)');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B1133D1A3E7');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B1133D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B1133D1A3E7');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B1133D1A3E7 FOREIGN KEY (tournament_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE round_result DROP FOREIGN KEY FK_D7DDF8BFBC902D37');
        $this->addSql('DROP INDEX IDX_D7DDF8BFBC902D37 ON round_result');
        $this->addSql('ALTER TABLE round_result DROP black_participant_id');
    }
}
