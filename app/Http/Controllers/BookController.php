<?php

namespace App\Http\Controllers;

use App\Http\Services\RoyalAppsApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BookController extends Controller
{
    private RoyalAppsApiClient $apiClient;

    public function __construct(RoyalAppsApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
        $this->apiClient->setToken(Session::get('api_token'));
    }

    public function create(Request $request)
    {
        $authorsResponse = $this->apiClient->getAuthors();

        if (!$authorsResponse) {
            return redirect()->route('login')->with('error', 'API connection error');
        }

        $authors = $authorsResponse['items'] ?? [];
        $selectedAuthorId = $request->get('author_id');

        return view('books.create', compact('authors', 'selectedAuthorId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'release_date' => 'required|date',
            'description' => 'required|string',
            'isbn' => 'required|string|max:20',
            'format' => 'required|string|max:50',
            'number_of_pages' => 'required|integer|min:1',
            'author_id' => 'required|integer'
        ]);

        $bookData = [
            'author' => ['id' => (int)$request->author_id],
            'title' => $request->title,
            'release_date' => $request->release_date,
            'description' => $request->description,
            'isbn' => $request->isbn,
            'format' => $request->format,
            'number_of_pages' => (int)$request->number_of_pages,
        ];

        $response = $this->apiClient->createBook($bookData);

        if ($response) {
            return redirect()->route('authors.show', $request->author_id)
                ->with('success', 'Book added successfully!');
        }

        return back()->with('error', 'Error creating book.')
            ->withInput();
    }

    public function destroy(int $id)
    {
        $success = $this->apiClient->deleteBook($id);

        if ($success) {
            return redirect()->route('authors.index')
                ->with('success', 'Book deleted successfully.');
        }

        return redirect()->route('authors.index')
            ->with('error', 'Error deleting book.');
    }
}
