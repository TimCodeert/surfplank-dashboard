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
                    player_id,
                    player_name,
                    total_finished_maps,
                    total_points,
                    CASE 
                        WHEN total_points >= 40000 THEN 'Surf God'
                        WHEN total_points >= 20000 THEN 'Expert'
                        WHEN total_points >= 10000 THEN 'Advanced'
                        WHEN total_points >= 5000  THEN 'Regular'
                        WHEN total_points >= 2000  THEN 'Casual'
                        WHEN total_points >= 500   THEN 'Beginner'
                        ELSE 'Newbie'
                    END AS rank_title
                FROM v_player_points
                ORDER BY total_points DESC
                ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP VIEW IF EXISTS v_player_ranks");
    }
}


