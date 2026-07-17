<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260717084320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Player achievements';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE PlayerAchievements (id INT AUTO_INCREMENT NOT NULL, achievement_key VARCHAR(50) NOT NULL, player_id INT NOT NULL, INDEX IDX_511F2EDC99E6F5DF (player_id), UNIQUE INDEX UNIQ_511F2EDC99E6F5DFBBF2F0B8 (player_id, achievement_key), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE PlayerAchievements ADD CONSTRAINT FK_511F2EDC99E6F5DF FOREIGN KEY (player_id) REFERENCES Player (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE PlayerAchievements DROP FOREIGN KEY FK_511F2EDC99E6F5DF');
        $this->addSql('DROP TABLE PlayerAchievements');
    }
}
