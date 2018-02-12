<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180212063634 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE first_move (id INT AUTO_INCREMENT NOT NULL, group_id INT DEFAULT NULL, move VARCHAR(255) NOT NULL, INDEX IDX_18CC884CFE54D947 (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE round_result (id INT AUTO_INCREMENT NOT NULL, tournament_id INT DEFAULT NULL, round_id INT DEFAULT NULL, white_player_id INT DEFAULT NULL, first_move_id INT DEFAULT NULL, begin_timestamp BIGINT NOT NULL, end_timestamp BIGINT NOT NULL, black_time_spent_in_seconds BIGINT NOT NULL, white_time_spent_in_seconds BIGINT NOT NULL, INDEX IDX_D7DDF8BF33D1A3E7 (tournament_id), INDEX IDX_D7DDF8BFA6005CA0 (round_id), INDEX IDX_D7DDF8BF4D532BBD (white_player_id), INDEX IDX_D7DDF8BF7E492D54 (first_move_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, begin_timestamp BIGINT NOT NULL, end_timestamp BIGINT NOT NULL, place VARCHAR(255) NOT NULL, place_gps_x DOUBLE PRECISION NOT NULL, place_gps_y DOUBLE PRECISION NOT NULL, pairing_system INT NOT NULL, number_of_rounds INT NOT NULL, status INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE first_moves_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, fathers_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, avatar_src VARCHAR(255) NOT NULL, gender INT NOT NULL, birth_date DATE NOT NULL, `range` INT NOT NULL, city VARCHAR(255) NOT NULL, federation VARCHAR(255) NOT NULL, phone VARCHAR(25) NOT NULL, email VARCHAR(25) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE round (id INT AUTO_INCREMENT NOT NULL, tournament_id INT DEFAULT NULL, number INT NOT NULL, begin_timestamp BIGINT NOT NULL, end_timestamp BIGINT NOT NULL, INDEX IDX_C5EEEA3433D1A3E7 (tournament_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE first_move ADD CONSTRAINT FK_18CC884CFE54D947 FOREIGN KEY (group_id) REFERENCES first_moves_group (id)');
        $this->addSql('ALTER TABLE round_result ADD CONSTRAINT FK_D7DDF8BF33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE round_result ADD CONSTRAINT FK_D7DDF8BFA6005CA0 FOREIGN KEY (round_id) REFERENCES round (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE round_result ADD CONSTRAINT FK_D7DDF8BF4D532BBD FOREIGN KEY (white_player_id) REFERENCES player (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE round_result ADD CONSTRAINT FK_D7DDF8BF7E492D54 FOREIGN KEY (first_move_id) REFERENCES first_move (id)');
        $this->addSql('ALTER TABLE round ADD CONSTRAINT FK_C5EEEA3433D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE round_result DROP FOREIGN KEY FK_D7DDF8BF7E492D54');
        $this->addSql('ALTER TABLE round_result DROP FOREIGN KEY FK_D7DDF8BF33D1A3E7');
        $this->addSql('ALTER TABLE round DROP FOREIGN KEY FK_C5EEEA3433D1A3E7');
        $this->addSql('ALTER TABLE first_move DROP FOREIGN KEY FK_18CC884CFE54D947');
        $this->addSql('ALTER TABLE round_result DROP FOREIGN KEY FK_D7DDF8BF4D532BBD');
        $this->addSql('ALTER TABLE round_result DROP FOREIGN KEY FK_D7DDF8BFA6005CA0');
        $this->addSql('DROP TABLE first_move');
        $this->addSql('DROP TABLE round_result');
        $this->addSql('DROP TABLE tournament');
        $this->addSql('DROP TABLE first_moves_group');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE round');
    }
}
