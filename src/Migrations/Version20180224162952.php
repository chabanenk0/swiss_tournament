<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180224162952 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE round_result ADD result INT DEFAULT NULL');
        $this->addSql('ALTER TABLE participant ADD tournament_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B1133D1A3E7 FOREIGN KEY (tournament_id) REFERENCES player (id)');
        $this->addSql('CREATE INDEX IDX_D79F6B1133D1A3E7 ON participant (tournament_id)');
        $this->addSql('ALTER TABLE player CHANGE fathers_name fathers_name VARCHAR(100) DEFAULT NULL, CHANGE avatar_src avatar_src VARCHAR(255) DEFAULT NULL, CHANGE birth_date birth_date DATE DEFAULT NULL, CHANGE rang rang INT DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL, CHANGE federation federation VARCHAR(255) DEFAULT NULL, CHANGE phone phone VARCHAR(25) DEFAULT NULL, CHANGE email email VARCHAR(25) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B1133D1A3E7');
        $this->addSql('DROP INDEX IDX_D79F6B1133D1A3E7 ON participant');
        $this->addSql('ALTER TABLE participant DROP tournament_id');
        $this->addSql('ALTER TABLE player CHANGE fathers_name fathers_name VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, CHANGE avatar_src avatar_src VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE birth_date birth_date DATE NOT NULL, CHANGE rang rang INT NOT NULL, CHANGE city city VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE federation federation VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE phone phone VARCHAR(25) NOT NULL COLLATE utf8_unicode_ci, CHANGE email email VARCHAR(25) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE round_result DROP result');
    }
}
