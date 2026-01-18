<?php

namespace Saudiza\Fatoora\Console;

use Illuminate\Console\Command;

class ZatcaInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zatca:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install ZATCA Laravel package';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Installing ZATCA Laravel Package...');

        // Publish configuration
        $this->call('vendor:publish', [
            '--tag' => 'zatca-config',
            '--force' => $this->option('force'),
        ]);

        // Publish migrations
        $this->call('vendor:publish', [
            '--tag' => 'zatca-migrations',
            '--force' => $this->option('force'),
        ]);

        // Run migrations
        if ($this->confirm('Do you want to run migrations now?', true)) {
            $this->call('migrate');
        }

        $this->info('ZATCA package installed successfully!');
        $this->line('');
        $this->info('Next steps:');
        $this->line('1. Update your .env file with ZATCA credentials');
        $this->line('2. Run: php artisan zatca:onboarding (for initial setup)');
        $this->line('3. Check config/zatca.php for all available options');

        return Command::SUCCESS;
    }
}
