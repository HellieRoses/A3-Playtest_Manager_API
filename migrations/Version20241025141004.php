<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241025141004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participation (playtest_id INT NOT NULL, player_id INT NOT NULL, INDEX IDX_AB55E24F2548C39A (playtest_id), INDEX IDX_AB55E24F99E6F5DF (player_id), UNIQUE INDEX UNIQ_IDENTIFIER_REGISTRATION (playtest_id, player_id), PRIMARY KEY(playtest_id, player_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F2548C39A FOREIGN KEY (playtest_id) REFERENCES playtest (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A7F1849495');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A7F78E8FBC');
        $this->addSql('DROP TABLE registration');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE registration (playtests_id INT NOT NULL, players_id INT NOT NULL, INDEX IDX_62A8A7A7F1849495 (players_id), INDEX IDX_62A8A7A7F78E8FBC (playtests_id), PRIMARY KEY(playtests_id, players_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A7F1849495 FOREIGN KEY (players_id) REFERENCES player (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A7F78E8FBC FOREIGN KEY (playtests_id) REFERENCES playtest (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F2548C39A');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F99E6F5DF');
        $this->addSql('DROP TABLE participation');
    }
}
