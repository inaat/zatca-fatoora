<?php

namespace Saudiza\Fatoora\Console;

use Illuminate\Console\Command;
use Saudiza\Fatoora\Facades\Zatca;

class ZatcaOnboardingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zatca:onboarding {--otp= : ZATCA OTP code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run ZATCA onboarding process to get certificates';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting ZATCA Onboarding Process...');
        $this->line('');

        // Validate configuration
        if (!$this->validateConfiguration()) {
            return Command::FAILURE;
        }

        // Get OTP
        $otp = $this->option('otp') ?? $this->ask('Enter your ZATCA OTP code');

        if (empty($otp)) {
            $this->error('OTP is required for onboarding');
            return Command::FAILURE;
        }

        try {
            $this->info('Generating CSR and requesting certificates...');

            $onboarding = Zatca::onboarding();
            $onboarding->setAuthOtp($otp);

            $result = $onboarding->getAuthorization();

            if ($result['success']) {
                $this->info('Onboarding successful!');
                $this->line('');
                $this->displayResults($result['data']);

                if ($this->confirm('Do you want to save these credentials to .env file?', true)) {
                    $this->saveToEnv($result['data']);
                }

                return Command::SUCCESS;
            } else {
                $this->error('Onboarding failed: ' . $result['message']);
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('Error during onboarding: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Validate required configuration
     *
     * @return bool
     */
    protected function validateConfiguration(): bool
    {
        $required = [
            'zatca.organization.vat_number',
            'zatca.organization.common_name',
            'zatca.organization.organization_name',
            'zatca.egs.serial_number',
        ];

        foreach ($required as $key) {
            if (empty(config($key))) {
                $this->error("Missing required configuration: {$key}");
                $this->line("Please update your config/zatca.php or .env file");
                return false;
            }
        }

        return true;
    }

    /**
     * Display onboarding results
     *
     * @param array $data
     * @return void
     */
    protected function displayResults(array $data): void
    {
        $this->info('Compliance Certificate:');
        $this->line($data['complianceCertificate']);
        $this->line('');

        $this->info('Production Certificate:');
        $this->line($data['productionCertificate']);
        $this->line('');

        $this->info('Private Key (Base64):');
        $this->line(substr($data['privateKey'], 0, 50) . '...');
        $this->line('');
    }

    /**
     * Save credentials to .env file
     *
     * @param array $data
     * @return void
     */
    protected function saveToEnv(array $data): void
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            $this->warn('.env file not found');
            return;
        }

        $env = file_get_contents($envPath);

        $updates = [
            'ZATCA_COMPLIANCE_CERTIFICATE' => $data['complianceCertificate'],
            'ZATCA_COMPLIANCE_SECRET' => $data['complianceSecret'],
            'ZATCA_PRODUCTION_CERTIFICATE' => $data['productionCertificate'],
            'ZATCA_PRODUCTION_SECRET' => $data['productionCertificateSecret'],
            'ZATCA_PRIVATE_KEY' => $data['privateKey'],
        ];

        foreach ($updates as $key => $value) {
            if (str_contains($env, $key . '=')) {
                // Update existing
                $env = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}=\"{$value}\"",
                    $env
                );
            } else {
                // Add new
                $env .= "\n{$key}=\"{$value}\"";
            }
        }

        file_put_contents($envPath, $env);

        $this->info('Credentials saved to .env file');
    }
}
