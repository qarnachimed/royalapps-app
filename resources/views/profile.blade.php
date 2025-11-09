@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-user mr-2 text-blue-600"></i>My Profile
            </h1>
        </div>

        <div class="card p-6">
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user text-blue-600 text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900">
                    {{ $profile['first_name'] ?? '' }} {{ $profile['last_name'] ?? '' }}
                </h2>
                <p class="text-gray-600">{{ $profile['email'] ?? '' }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 p-4 rounded">
                    <h3 class="font-semibold text-gray-700 mb-2">General Information</h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><strong>Gender:</strong> {{ $profile['gender'] ?? 'Not specified' }}</p>
                        <p><strong>Birth Date:</strong>
                            {{ isset($profile['birthday']) && $profile['birthday'] ? \Carbon\Carbon::parse($profile['birthday'])->format('d/m/Y') : 'Not specified' }}
                        </p>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded">
                    <h3 class="font-semibold text-gray-700 mb-2">Location</h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><strong>Place of Birth:</strong> {{ $profile['place_of_birth'] ?? 'Not specified' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection