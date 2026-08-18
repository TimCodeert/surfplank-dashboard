<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260818121357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Player points';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
                CREATE OR REPLACE VIEW v_player_ranks AS
                SELECT 
                    pp.player_id,
                    pp.player_name,
                    pp.total_finished_maps,
                    pp.total_points,
                    DENSE_RANK() OVER (ORDER BY pp.total_points DESC) AS position,
                    CASE 
                        WHEN pp.total_points >= 10000 THEN 'Surf God'
                        WHEN pp.total_points >= 5000  THEN 'Pro'
                        WHEN pp.total_points >= 2500  THEN 'Advanced'
                        WHEN pp.total_points >= 1000  THEN 'Regular'
                        WHEN pp.total_points >= 500   THEN 'Beginner'
                        WHEN pp.total_points >= 100   THEN 'Novice'
                        ELSE 'Newbie'
                    END AS rank_title
                FROM v_player_points pp
                ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP VIEW IF EXISTS v_player_ranks");
    }
}


