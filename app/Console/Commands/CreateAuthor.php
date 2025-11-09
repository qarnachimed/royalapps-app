<?php

namespace App\Console\Commands;

use App\Http\Services\RoyalAppsApiClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateAuthor extends Command
{
    protected $signature = 'author:create 
                            {first_name : Prénom de l\'auteur}
                            {last_name : Nom de l\'auteur}
                            {--birthday= : Date de naissance (YYYY-MM-DD)}
                            {--gender= : Genre (male/female)}
                            {--place_of_birth= : Lieu de naissance}';

    protected $description = 'Créer un nouvel auteur via l\'API Royal Apps';

    public function handle(RoyalAppsApiClient $apiClient)
    {
        $this->info('🔗 Connexion à l\'API Royal Apps...');

        try {
            $email = 'ahsoka.tano@royal-apps.io';
            $password = 'Kryze4President';

            $this->line("Tentative de connexion avec: {$email}");

            $loginResponse = $apiClient->login($email, $password);

            if (!$loginResponse || !isset($loginResponse['token_key'])) {
                $this->error('❌ Échec de la connexion à l\'API');
                $this->line('Vérifiez les credentials dans le fichier .env');
                $this->line('Email: ' . env('ROYAL_APPS_EMAIL'));
                $this->line('URL API: ' . env('ROYAL_APPS_API_URL'));
                return 1;
            }

            $this->info('✅ Connexion réussie!');
            $apiClient->setToken($loginResponse['token_key']);

            $authorData = [
                'first_name' => $this->argument('first_name'),
                'last_name' => $this->argument('last_name'),
            ];

            if ($this->option('birthday')) {
                $authorData['birthday'] = $this->option('birthday');
                $this->line("📅 Date de naissance: {$authorData['birthday']}");
            }

            if ($this->option('gender')) {
                $authorData['gender'] = $this->option('gender');
                $this->line("⚧ Genre: {$authorData['gender']}");
            }

            if ($this->option('place_of_birth')) {
                $authorData['place_of_birth'] = $this->option('place_of_birth');
                $this->line("📍 Lieu de naissance: {$authorData['place_of_birth']}");
            }

            $this->info('📝 Création de l\'auteur...');
            $this->line("Nom: {$authorData['first_name']} {$authorData['last_name']}");

            $response = $apiClient->createAuthor($authorData);

            if ($response) {
                $this->info('✅ Auteur créé avec succès!');
                $this->line("🆔 ID: {$response['id']}");
                $this->line("👤 Nom: {$response['first_name']} {$response['last_name']}");

                if (isset($response['birthday'])) {
                    $this->line("📅 Date de naissance: {$response['birthday']}");
                }
                if (isset($response['gender'])) {
                    $this->line("⚧ Genre: {$response['gender']}");
                }
                if (isset($response['place_of_birth'])) {
                    $this->line("📍 Lieu de naissance: {$response['place_of_birth']}");
                }

                return 0;
            }

            $this->error('❌ Erreur lors de la création de l\'auteur');
            return 1;

        } catch (\Exception $e) {
            $this->error('💥 Erreur: ' . $e->getMessage());
            Log::error('Command CreateAuthor failed: ' . $e->getMessage());
            return 1;
        }
    }
}
