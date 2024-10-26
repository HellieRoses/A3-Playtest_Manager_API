<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241026164635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F2548C39A');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F99E6F5DF');
        $this->addSql('ALTER TABLE participation ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F2548C39A FOREIGN KEY (playtest_id) REFERENCES playtest (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participation RENAME INDEX uniq_identifier_registration TO UNIQ_IDENTIFIER_PARTICIPATION');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participation MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F2548C39A');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F99E6F5DF');
        $this->addSql('DROP INDEX `PRIMARY` ON participation');
        $this->addSql('ALTER TABLE participation DROP id');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F2548C39A FOREIGN KEY (playtest_id) REFERENCES playtest (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE participation ADD PRIMARY KEY (playtest_id, player_id)');
        $this->addSql('ALTER TABLE participation RENAME INDEX uniq_identifier_participation TO UNIQ_IDENTIFIER_REGISTRATION');
    }
}
