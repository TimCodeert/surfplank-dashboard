<?php

namespace App\Command;

use App\Service\ServerStatusService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:update-server-status',
    description: 'Updates the CS2 server status cache in the background.'
)]
class UpdateServerStatusCommand extends Command
{
    public function __construct(
        private ServerStatusService $serverStatusService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->serverStatusService->refreshCache();
        
        $output->writeln('Server status cache successfully updated!');
        return Command::SUCCESS;
    }
}