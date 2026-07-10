<?php

namespace App\Service;

use App\Repository\PlayerRepository;
use App\Mapper\ServerStatusMapper;
use xPaw\SourceQuery\SourceQuery;
use Psr\Log\LoggerInterface;

class ServerStatusService
{
    public function __construct(
        private LoggerInterface $logger,
        private PlayerRepository $playerRepository,
        private ServerStatusMapper $statusMapper,
        private string $serverIp,
        private int $serverPort
    ) {}

    public function getServerStatus(): array
    {
        $serverInfo = [];
        $query = new SourceQuery();

        try {
            $query->Connect($this->serverIp, $this->serverPort, 1, SourceQuery::SOURCE);
            $serverInfo = $query->GetInfo() ?: [];
        } catch (\Exception $e) {
            $this->logger->warning('Failed to retrieve server info: ' . $e->getMessage());
        } finally {
            $query->Disconnect();
        }

        $playerCount = $serverInfo['Players'] ?? 0;
        $players = $this->playerRepository->findActivePlayers($playerCount);

        return $this->statusMapper->map($serverInfo, $players);
    }
}