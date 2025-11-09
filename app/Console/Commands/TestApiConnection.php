<?php

namespace App\Console\Commands;

use App\Http\Services\RoyalAppsApiClient;
use Illuminate\Console\Command;

class TestApiConnection extends Command
{
    protected $signature = 'api:test';
    protected $description = 'Tester la connexion à l\'API Royal Apps';

    public function handle(RoyalAppsApiClient $apiClient)
    {
        $this->info('🧪 Test de connexion à l\'API Royal Apps...');

        $this->line("📧 Email: " . env('ROYAL_APPS_EMAIL'));
        $this->line("🔗 URL: " . env('ROYAL_APPS_API_URL'));

        $response = $apiClient->login(
            env('ROYAL_APPS_EMAIL'),
            env('ROYAL_APPS_PASSWORD')
        );

        if ($response && isset($response['token_key'])) {
            $this->info('✅ CONNEXION RÉUSSIE!');
            $this->line("🔐 Token: " . substr($response['token_key'], 0, 20) . '...');
            $this->line("👤 Utilisateur: {$response['user']['first_name']} {$response['user']['last_name']}");
            $this->line("📧 Email: {$response['user']['email']}");
            return 0;
        }

        $this->error('❌ ÉCHEC DE LA CONNEXION');
        $this->line('Vérifiez:');
        $this->line('- La connexion internet');
        $this->line('- Les credentials dans .env');
        $this->line('- L\'URL de l\'API');
        return 1;
    }
}
