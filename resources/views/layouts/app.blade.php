<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Royal Apps - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .btn-primary {
            @apply bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition;
        }
        .btn-danger {
            @apply bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded transition;
        }
        .card {
            @apply bg-white rounded-lg shadow-md border border-gray-200;
        }
    </style>
</head>
<body class="bg-gray-50">
<nav class="bg-white shadow-sm border-b">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <i class="fas fa-crown text-blue-600 text-xl mr-2"></i>
                <span class="text-xl font-bold text-gray-800">Royal Apps</span>
            </div>

            @if(session('user'))
                <div class="flex items-center space-x-6">
                    <a href="{{ route('authors.index') }}" class="text-gray-600 hover:text-blue-600">Authors</a>
                    <a href="{{ route('books.create') }}" class="text-gray-600 hover:text-blue-600">New Book</a>
                </div>

                <div class="flex items-center space-x-4">
                        <span class="text-gray-700">
                            {{ session('user')['first_name'] }} {{ session('user')['last_name'] }}
                        </span>
                    <a href="{{ route('profile') }}" class="text-gray-600 hover:text-blue-600">
                        <i class="fas fa-user"></i>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-red-600">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</nav>

<main class="max-w-7xl mx-auto py-6 px-4">
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    @yield('content')
</main>
</body>
</html>