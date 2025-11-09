@extends('layouts.app')

@section('title', 'Authors List')

@section('content')
    <div class="mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Authors List</h1>
                <p class="text-gray-600 mt-1">Manage authors and browse their books</p>
            </div>
            <a href="{{ route('books.create') }}" class="btn-primary mt-4 lg:mt-0">
                <i class="fas fa-plus mr-2"></i>New Book
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-blue-500 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Authors</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalAuthors }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-book text-green-500 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Authors with Books</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $authorsWithBooks }}
                            @if($totalAuthors > 0)
                                <span class="text-sm font-normal text-gray-500">
                        ({{ number_format(($authorsWithBooks / $totalAuthors) * 100, 1) }}%)
                    </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-plus text-purple-500 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Authors without Books</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $authorsWithoutBooks }}
                            @if($totalAuthors > 0)
                                <span class="text-sm font-normal text-gray-500">
                        ({{ number_format(($authorsWithoutBooks / $totalAuthors) * 100, 1) }}%)
                    </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Birth Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Place of Birth</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Books</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($authors as $author)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $author['first_name'] }} {{ $author['last_name'] }}
                                    </div>
                                    <div class="text-sm text-gray-500 capitalize">
                                        {{ $author['gender'] ?? 'Not specified' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $author['birthday'] ? \Carbon\Carbon::parse($author['birthday'])->format('d/m/Y') : 'Not specified' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $author['place_of_birth'] ?? 'Not specified' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $author['book_count'] > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $author['book_count'] }} book{{ $author['book_count'] !== 1 ? 's' : '' }}
                        </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('authors.show', $author['id']) }}"
                               class="text-blue-600 hover:text-blue-900 mr-4 transition duration-150">
                                <i class="fas fa-eye mr-1"></i>View
                            </a>
                            @if($author['book_count'] === 0)
                                <form action="{{ route('authors.destroy', $author['id']) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-900 transition duration-150"
                                            onclick="return confirm('Delete {{ $author['first_name'] }} {{ $author['last_name'] }}?')">
                                        <i class="fas fa-trash mr-1"></i>Delete
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-400 cursor-not-allowed" title="Author has books">
                            <i class="fas fa-trash mr-1"></i>Delete
                        </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-users text-gray-400 text-4xl mb-3"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">No Authors Found</h3>
                                <p class="text-gray-500">There are no authors in the system yet.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($totalPages > 1)
        <div class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing page {{ $currentPage }} of {{ $totalPages }}
            </div>

            <div class="flex space-x-2">
                @if($currentPage > 1)
                    <a href="{{ route('authors.index', ['page' => $currentPage - 1]) }}"
                       class="px-3 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-500 hover:bg-gray-50 transition duration-150">
                        <i class="fas fa-chevron-left mr-1"></i> Previous
                    </a>
                @else
                    <span class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm font-medium text-gray-400 cursor-not-allowed">
                <i class="fas fa-chevron-left mr-1"></i> Previous
            </span>
                @endif

                <div class="hidden sm:flex space-x-2">
                    @for($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++)
                        @if($i == $currentPage)
                            <span class="px-3 py-2 bg-blue-600 border border-blue-600 rounded-md text-sm font-medium text-white">
                        {{ $i }}
                    </span>
                        @else
                            <a href="{{ route('authors.index', ['page' => $i]) }}"
                               class="px-3 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-500 hover:bg-gray-50 transition duration-150">
                                {{ $i }}
                            </a>
                        @endif
                    @endfor
                </div>

                @if($currentPage < $totalPages)
                    <a href="{{ route('authors.index', ['page' => $currentPage + 1]) }}"
                       class="px-3 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-500 hover:bg-gray-50 transition duration-150">
                        Next <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                @else
                    <span class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm font-medium text-gray-400 cursor-not-allowed">
                Next <i class="fas fa-chevron-right ml-1"></i>
            </span>
                @endif
            </div>
        </div>
    @endif
@endsection