<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241021092930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player_video_game DROP FOREIGN KEY FK_7B19E86D16230A8');
        $this->addSql('ALTER TABLE player_video_game DROP FOREIGN KEY FK_7B19E86D99E6F5DF');
        $this->addSql('DROP TABLE player_video_game');
        $this->addSql('ALTER TABLE player ADD favorite_games LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD login VARCHAR(255) NOT NULL, ADD password VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_LOGIN ON player (login)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON player (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE player_video_game (player_id INT NOT NULL, video_game_id INT NOT NULL, INDEX IDX_7B19E86D99E6F5DF (player_id), INDEX IDX_7B19E86D16230A8 (video_game_id), PRIMARY KEY(player_id, video_game_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE player_video_game ADD CONSTRAINT FK_7B19E86D16230A8 FOREIGN KEY (video_game_id) REFERENCES video_game (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player_video_game ADD CONSTRAINT FK_7B19E86D99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_LOGIN ON player');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON player');
        $this->addSql('ALTER TABLE player DROP favorite_games, DROP login, DROP password');
    }
}
