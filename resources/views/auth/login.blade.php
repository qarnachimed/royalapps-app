@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full">
            <div class="card p-8">
                <div class="text-center mb-8">
                    <i class="fas fa-crown text-blue-600 text-4xl mb-4"></i>
                    <h2 class="text-2xl font-bold text-gray-900">Login</h2>
                    <p class="text-gray-600 mt-2">Access your Royal Apps account</p>
                </div>

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                                   value="ahsoka.tano@royal-apps.io">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" name="password" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                                   value="Kryze4President">
                        </div>

                        <button type="submit" class="w-full btn-primary">
                            <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection