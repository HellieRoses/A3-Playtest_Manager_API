<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241018130135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, adress VARCHAR(255) NOT NULL, contact VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, birthday_date DATE NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player_video_game (player_id INT NOT NULL, video_game_id INT NOT NULL, INDEX IDX_7B19E86D99E6F5DF (player_id), INDEX IDX_7B19E86D16230A8 (video_game_id), PRIMARY KEY(player_id, video_game_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playtest (id INT AUTO_INCREMENT NOT NULL, video_game_id INT NOT NULL, company_id INT NOT NULL, begin DATETIME NOT NULL, end DATETIME NOT NULL, adress VARCHAR(255) NOT NULL, INDEX IDX_4B35973916230A8 (video_game_id), INDEX IDX_4B359739979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video_game (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, support VARCHAR(255) NOT NULL, INDEX IDX_24BC6C50979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE player_video_game ADD CONSTRAINT FK_7B19E86D99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player_video_game ADD CONSTRAINT FK_7B19E86D16230A8 FOREIGN KEY (video_game_id) REFERENCES video_game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE playtest ADD CONSTRAINT FK_4B35973916230A8 FOREIGN KEY (video_game_id) REFERENCES video_game (id)');
        $this->addSql('ALTER TABLE playtest ADD CONSTRAINT FK_4B359739979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE video_game ADD CONSTRAINT FK_24BC6C50979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player_video_game DROP FOREIGN KEY FK_7B19E86D99E6F5DF');
        $this->addSql('ALTER TABLE player_video_game DROP FOREIGN KEY FK_7B19E86D16230A8');
        $this->addSql('ALTER TABLE playtest DROP FOREIGN KEY FK_4B35973916230A8');
        $this->addSql('ALTER TABLE playtest DROP FOREIGN KEY FK_4B359739979B1AD6');
        $this->addSql('ALTER TABLE video_game DROP FOREIGN KEY FK_24BC6C50979B1AD6');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE player_video_game');
        $this->addSql('DROP TABLE playtest');
        $this->addSql('DROP TABLE video_game');
    }
}
