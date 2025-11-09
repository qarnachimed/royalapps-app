<?php

namespace App\Http\Controllers;

use App\Http\Services\RoyalAppsApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    private RoyalAppsApiClient $apiClient;

    public function __construct(RoyalAppsApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function showLogin()
    {
        if (Session::has('api_token')) {
            return redirect()->route('authors.index');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $response = $this->apiClient->login(
            $request->email,
            $request->password
        );

        if ($response && isset($response['token_key'])) {
            Session::put('api_token', $response['token_key']);
            Session::put('user', $response['user']);

            return redirect()->route('authors.index')
                ->with('success', 'Login successful!');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->withInput();
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login')
            ->with('success', 'Logout successful.');
    }
}
