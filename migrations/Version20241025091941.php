<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241025091941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE registration (playtests_id INT NOT NULL, players_id INT NOT NULL, INDEX IDX_62A8A7A7F78E8FBC (playtests_id), INDEX IDX_62A8A7A7F1849495 (players_id), PRIMARY KEY(playtests_id, players_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A7F78E8FBC FOREIGN KEY (playtests_id) REFERENCES playtest (id)');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A7F1849495 FOREIGN KEY (players_id) REFERENCES player (id)');
        $this->addSql('DROP TABLE refresh_tokens');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, username VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A7F78E8FBC');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A7F1849495');
        $this->addSql('DROP TABLE registration');
    }
}
