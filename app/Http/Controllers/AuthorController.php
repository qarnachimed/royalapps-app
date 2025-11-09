<?php

namespace App\Http\Controllers;

use App\Http\Services\RoyalAppsApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthorController extends Controller
{
    private RoyalAppsApiClient $apiClient;

    public function __construct(RoyalAppsApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
        $this->apiClient->setToken(Session::get('api_token'));
    }

    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $limit = 10;

        $allAuthorsResponse = $this->apiClient->getAuthors([
            'limit' => 1000,
            'page' => 1
        ]);

        if (!$allAuthorsResponse) {
            return redirect()->route('login')->with('error', 'API connection error');
        }

        $allAuthors = $allAuthorsResponse['items'] ?? [];
        $totalAuthors = $allAuthorsResponse['total_results'] ?? count($allAuthors);

        $globalAuthorsWithBooks = 0;
        $globalAuthorsWithoutBooks = 0;

        foreach ($allAuthors as $author) {
            if (!isset($author['books'])) {
                $authorDetails = $this->apiClient->getAuthor($author['id']);
                $bookCount = count($authorDetails['books'] ?? []);
            } else {
                $bookCount = count($author['books'] ?? []);
            }

            if ($bookCount > 0) {
                $globalAuthorsWithBooks++;
            } else {
                $globalAuthorsWithoutBooks++;
            }
        }

        $paginatedResponse = $this->apiClient->getAuthors([
            'page' => $page,
            'limit' => $limit
        ]);

        $paginatedAuthors = $paginatedResponse['items'] ?? [];
        $totalPages = $paginatedResponse['total_pages'] ?? 1;
        $currentPage = $paginatedResponse['current_page'] ?? 1;

        $authorsWithBookCount = [];
        foreach ($paginatedAuthors as $author) {
            if (!isset($author['books'])) {
                $authorDetails = $this->apiClient->getAuthor($author['id']);
                $bookCount = count($authorDetails['books'] ?? []);
            } else {
                $bookCount = count($author['books'] ?? []);
            }

            $authorsWithBookCount[] = [
                'id' => $author['id'],
                'first_name' => $author['first_name'],
                'last_name' => $author['last_name'],
                'birthday' => $author['birthday'] ?? null,
                'gender' => $author['gender'] ?? null,
                'place_of_birth' => $author['place_of_birth'] ?? null,
                'book_count' => $bookCount
            ];
        }

        return view('authors.index', [
            'authors' => $authorsWithBookCount,
            'totalAuthors' => $totalAuthors,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'authorsWithBooks' => $globalAuthorsWithBooks,
            'authorsWithoutBooks' => $globalAuthorsWithoutBooks
        ]);
    }

    public function show(int $id)
    {
        $author = $this->apiClient->getAuthor($id);

        if (!$author) {
            return redirect()->route('authors.index')
                ->with('error', 'Author not found.');
        }

        return view('authors.show', compact('author'));
    }

    public function destroy(int $id)
    {
        $author = $this->apiClient->getAuthor($id);

        if (!$author) {
            return back()->with('error', 'Author not found.');
        }

        $bookCount = count($author['books'] ?? []);
        if ($bookCount > 0) {
            return back()->with('error', 'Cannot delete author with books.');
        }

        $success = $this->apiClient->deleteAuthor($id);

        if ($success) {
            return redirect()->route('authors.index')
                ->with('success', 'Author deleted successfully.');
        }

        return back()->with('error', 'Error deleting author.');
    }
}
