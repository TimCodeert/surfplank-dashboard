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
                    CREATE OR REPLACE VIEW v_player_points AS
                    SELECT 
                        p.id as player_id,
                        p.name AS player_name,
                        COUNT(v.map_time_id) AS total_finished_maps,
                        SUM(
                            50 
                            + ROUND(((v.total_completions - v.worldwide_rank + 1) / v.total_completions) * 100)
                            + IF(v.worldwide_rank = 1, 200, 0)
                        ) AS total_points
                    FROM v_ranked_maptimes v
                    JOIN MapTimes mt ON mt.id = v.map_time_id WHERE mt.type = 0
                    JOIN Player p ON p.id = mt.player_id 
                    JOIN Maps m ON m.id = mt.map_id WHERE m.ranked = 1
                    GROUP BY p.id
                ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP VIEW IF EXISTS v_player_points");
    }
}
