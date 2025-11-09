@extends('layouts.app')

@section('title', 'New Book')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-book-medical mr-2 text-blue-600"></i>
                @if($selectedAuthorId)
                    Add Book for Selected Author
                @else
                    Add New Book
                @endif
            </h1>
            <p class="text-gray-600 mt-1">
                @if($selectedAuthorId)
                    You're adding a book for a specific author
                @else
                    Select an author and fill in the book information
                @endif
            </p>
        </div>

        <div class="card p-6">
            <form method="POST" action="{{ route('books.store') }}">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="author_id" class="block text-sm font-medium text-gray-700 mb-2">Author *</label>

                        @if($selectedAuthorId)
                            @php
                                $selectedAuthor = collect($authors)->firstWhere('id', $selectedAuthorId);
                            @endphp

                            @if($selectedAuthor)
                                <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg mb-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-user text-blue-600 mr-3"></i>
                                        <div>
                                            <p class="font-medium text-blue-900">
                                                {{ $selectedAuthor['first_name'] }} {{ $selectedAuthor['last_name'] }}
                                            </p>
                                            <p class="text-sm text-blue-700">
                                                You're adding a book for this author
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="author_id" value="{{ $selectedAuthorId }}">
                            @else
                                <select id="author_id" name="author_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select an author</option>
                                    @foreach($authors as $author)
                                        <option value="{{ $author['id'] }}" {{ $selectedAuthorId == $author['id'] ? 'selected' : '' }}>
                                            {{ $author['first_name'] }} {{ $author['last_name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        @else
                            <select id="author_id" name="author_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select an author</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author['id'] }}">
                                        {{ $author['first_name'] }} {{ $author['last_name'] }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                        <input type="text" id="title" name="title" required
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Book title">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea id="description" name="description" rows="4" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Book description"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label for="release_date" class="block text-sm font-medium text-gray-700 mb-2">Release Date *</label>
                            <input type="date" id="release_date" name="release_date" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="isbn" class="block text-sm font-medium text-gray-700 mb-2">ISBN *</label>
                            <input type="text" id="isbn" name="isbn" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="ISBN">
                        </div>

                        <div>
                            <label for="format" class="block text-sm font-medium text-gray-700 mb-2">Format *</label>
                            <input type="text" id="format" name="format" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Format">
                        </div>

                        <div>
                            <label for="number_of_pages" class="block text-sm font-medium text-gray-700 mb-2">Number of Pages *</label>
                            <input type="number" id="number_of_pages" name="number_of_pages" required min="1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Number of pages">
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    @if($selectedAuthorId)
                        <a href="{{ route('authors.show', $selectedAuthorId) }}"
                           class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded transition">
                            Back to Author
                        </a>
                    @else
                        <a href="{{ route('authors.index') }}"
                           class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded transition">
                            Cancel
                        </a>
                    @endif
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>Create Book
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection