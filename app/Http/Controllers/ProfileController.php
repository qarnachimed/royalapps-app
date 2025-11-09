<?php

namespace App\Http\Controllers;

use App\Http\Services\RoyalAppsApiClient;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    private RoyalAppsApiClient $apiClient;

    public function __construct(RoyalAppsApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
        $this->apiClient->setToken(Session::get('api_token'));
    }

    public function show()
    {
        $profile = $this->apiClient->getProfile();

        if (!$profile) {
            return redirect()->route('login')->with('error', 'Erreur de connexion à l\'API');
        }

        $profile = array_merge([
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'gender' => null,
            'birthday' => null,
            'place_of_birth' => null,
        ], $profile);

        return view('profile', compact('profile'));
    }
}
