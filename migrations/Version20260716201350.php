<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260716201350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the v_ranked_maptimes view for player ranks and completions';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
                    CREATE VIEW v_ranked_maptimes AS
                    SELECT 
                        id as map_time_id,
                        COUNT(*) OVER (PARTITION BY map_id, style, type, stage) as total_completions,
                        RANK() OVER (PARTITION BY map_id, style, type, stage ORDER BY run_time ASC) as worldwide_rank
                    FROM MapTimes
                ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP VIEW IF EXISTS v_ranked_maptimes");
    }
}
