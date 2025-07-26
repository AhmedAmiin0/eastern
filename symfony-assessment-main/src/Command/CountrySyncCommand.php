<?php
declare(strict_types=1);

namespace App\Command;

use App\Service\CountrySyncService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CountrySyncCommand extends Command
{
    private CountrySyncService $countrySyncService;

    public function __construct(CountrySyncService $countrySyncService)
    {
        parent::__construct();
        $this->countrySyncService = $countrySyncService;
    }

    protected function configure(): void
    {
        $this->setName('countries:sync');
        $this->setDescription('Synchronize countries from REST Countries API and reset modified data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $io->info('Starting country synchronization...');
        $io->note('This will reset all countries to their original data from REST Countries API');
        
        try {
            $syncedCount = $this->countrySyncService->syncCountries();
            $io->success("Successfully synced {$syncedCount} countries to the database.");
            $io->info('All countries have been reset to their original data from REST Countries API');
        } catch (\Exception $e) {
            $io->error('Error syncing countries: ' . $e->getMessage());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}