<?php 

namespace App\Controller;

use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\ArrayList;
use Symfony\Component\HttpClient\HttpClient;

class BookController extends Controller
{
    private static $allowed_actions = [
        'index',
        'show',
        'add',
        'edit',
        'delete',
    ];

    public function index(HTTPRequest $request)
    {
        $query = $request->getVar("q");
        $startIndex = $request->getVar("startIndex");

        $client = HttpClient::create();
        $content = "";
        $books = false;
        $pagination = false;
        $maxResults = 9;

        if ($query) {
            $basicQuery = 'maxResults=' . $maxResults . '&q=' . $query;
            $bookQuery = $basicQuery;
            
            if ($startIndex) {
                $bookQuery .= '&startIndex=' . $startIndex;
            }

            $response = $client->request('GET', 'https://www.googleapis.com/books/v1/volumes?' . $bookQuery);
            $status = $response->getStatusCode();

            if ($status == 200) {
                $content = $response->toArray();
                $books = array_map(function ($book) {
                    $authors = $book['volumeInfo']['authors'] ?? [];

                    return [
                        'title' => $book['volumeInfo']['title'] ?? '',
                        'publishedDate' => $book['volumeInfo']['publishedDate'] ?? '',
                        'authors' => ($authors ? ArrayList::create(
                            array_map(function ($author) {
                                return ['AuthorName' => $author ?? ''];
                            }, $book['volumeInfo']['authors'])
                        ) : ''),
                        'language' => $book['volumeInfo']['language'] ?? '',
                        'image' => $book['volumeInfo']['imageLinks']['thumbnail'] ?? '',
                        'pageCount' => $book['volumeInfo']['pageCount'] ?? '',
                        'categories' => ArrayList::create(
                            array_map(function ($category) {
                                return ['CategoryName' => $category ?? ''];
                            }, $book['volumeInfo']['categories'] ?? [])
                        ),
                        'description' => $book['volumeInfo']['description'] ?? '',
                    ];
                }, $content['items']);

                $books = ArrayList::create($books);

                $pagination = $this->paginator('/books?' . $basicQuery, $content['totalItems'], $startIndex, $maxResults);
                $pagination['pages'] = ArrayList::create($pagination['pages']);
                $pagination = ArrayList::create([$pagination]);

            }
        }
        return $this->customise(['books' => $books, 'pagination' => $pagination])->renderWith('Book');
    }

    protected function paginator($query, $count, $startIndex, $perPage): array
    {
        $pagination = [
            'start' => false,
            'current' => false,
            'previous' => false,
            'next' => false,
            'totalPages' => 0,
            'pages' => false,
        ];

        $totalPages = ceil($count / $perPage);

        $currentPage = ceil($startIndex / $perPage) + 1;

        $previousIndex = $startIndex - $perPage;
        if ($previousIndex < 0) {
            $previousIndex = false;
        }

        $nextIndex = $perPage * ($currentPage);
        if ($nextIndex > $count) {
            $nextIndex = false;
        }

        $pagination['start'] = [
            'page' => $previousIndex > 0 ? 1 : false,
            'link' => $previousIndex > 0 ? $query . '&startIndex=0' : false,
        ];

        $pagination['current'] = [
            'page' => $currentPage,
            'link' => $query . '&startIndex=' . $startIndex
        ];
        $pagination['previous'] = [
            'page' => $previousIndex !== false ? $currentPage - 1 : false,
            'link' => $previousIndex !== false ? $query . '&startIndex=' . $previousIndex : false,
        ];
        $pagination['next'] = [
            'page' => $nextIndex ? $currentPage + 1 : false,
            'link' => $nextIndex ? $query . '&startIndex=' . $nextIndex : false,
        ];

        $totalPages = ceil($count / $perPage);  
        $pagination['totalPages'] = $totalPages;
        $pages = [];

        for ($i = 0; $i < 3; $i++) {
            $page = $currentPage + $i - 1;

            if ($currentPage == 1) {
                $page = $currentPage + $i;
            }

            if ($page > $totalPages) {
                break;
            }
            if ($page < 1) {
                continue;
            }
            
            $pages[] = [
                'page' => $page,
                'link' => $query . '&startIndex=' . ($page - 1) * $perPage,
                'currentPage' => $page == $currentPage
            ];
            $pagination['pages'] = $pages;
        } 

        return $pagination;
    }
}