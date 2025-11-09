@extends('layouts.app')

@section('title', $author['first_name'] . ' ' . $author['last_name'])

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    {{ $author['first_name'] }} {{ $author['last_name'] }}
                </h1>
                <div class="flex items-center text-gray-600 space-x-4">
                    @if($author['birthday'])
                        <span class="flex items-center">
                        <i class="fas fa-birthday-cake mr-2"></i>
                        {{ \Carbon\Carbon::parse($author['birthday'])->format('d/m/Y') }}
                    </span>
                    @endif
                    @if($author['place_of_birth'])
                        <span class="flex items-center">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        {{ $author['place_of_birth'] }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('books.create', ['author_id' => $author['id']]) }}" class="btn-primary flex items-center">
                    <i class="fas fa-plus mr-2"></i>New Book
                </a>
                <a href="{{ route('authors.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded transition flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Authors
                </a>
            </div>
        </div>
    </div>

    @if(!empty($author['books']))
        <div class="card mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-book mr-3 text-blue-600"></i>
                    Books ({{ count($author['books']) }})
                </h2>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($author['books'] as $book)
                    <div class="px-6 py-4 hover:bg-gray-50 transition duration-200">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $book['title'] }}</h3>
                                <p class="text-gray-600 mb-3">{{ $book['description'] }}</p>
                                <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                        <span class="flex items-center">
                            <i class="fas fa-calendar mr-2 text-blue-500"></i>
                            {{ \Carbon\Carbon::parse($book['release_date'])->format('d/m/Y') }}
                        </span>
                                    <span class="flex items-center">
                            <i class="fas fa-barcode mr-2 text-green-500"></i>
                            ISBN: {{ $book['isbn'] }}
                        </span>
                                    <span class="flex items-center">
                            <i class="fas fa-book-open mr-2 text-purple-500"></i>
                            {{ $book['number_of_pages'] }} pages
                        </span>
                                    <span class="flex items-center">
                            <i class="fas fa-tag mr-2 text-orange-500"></i>
                            {{ $book['format'] }}
                        </span>
                                </div>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <form action="{{ route('books.destroy', $book['id']) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete \"{{ $book['title'] }}\"?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="author_id" value="{{ $author['id'] }}">
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-50 transition duration-200"
                                            title="Delete book">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="card p-8 text-center mb-6">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-book-open text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Books Yet</h3>
            <p class="text-gray-600 mb-6">This author doesn't have any books in their collection.</p>
            <a href="{{ route('books.create', ['author_id' => $author['id']]) }}"  class="btn-primary inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>Add First Book
            </a>
        </div>
    @endif

    @if(empty($author['books']))
        <div class="card p-6 border-l-4 border-red-400 bg-red-50">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-red-800 mb-1">Delete Author</h3>
                    <p class="text-red-600 text-sm">This author has no books associated. You can safely delete them.</p>
                </div>
                <form action="{{ route('authors.destroy', $author['id']) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn-danger flex items-center"
                            onclick="return confirm('Are you sure you want to permanently delete this author?')">
                        <i class="fas fa-trash mr-2"></i>Delete Author
                    </button>
                </form>
            </div>
        </div>
    @endif

    <style>
        .btn-primary {
            @apply bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 transform hover:scale-105;
        }

        .btn-danger {
            @apply bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200;
        }

        .card {
            @apply bg-white rounded-xl shadow-lg border border-gray-100;
        }
    </style>
@endsection