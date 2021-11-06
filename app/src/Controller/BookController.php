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
        $query = $request->getVar("query");

        $client = HttpClient::create();
        $content = "";
        $books = [];

        if ($query) {
            $response = $client->request('GET', 'https://www.googleapis.com/books/v1/volumes?q=' . $query);
            $content = $response->toArray();
            $books = array_map(function ($book) {
                return [
                    'title' => $book['volumeInfo']['title'] ?? '',
                    'publishedDate' => $book['volumeInfo']['publishedDate'] ?? '',
                    'authors' => ArrayList::create(
                        array_map(function ($author) {
                            return ['AuthorName' => $author ?? ''];
                        }, $book['volumeInfo']['authors'])
                    ),
                    'language' => $book['volumeInfo']['language'] ?? '',
                    'image' => $book['volumeInfo']['imageLinks']['smallThumbnail'],
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
        }
        // return json_encode($content);
        return $this->customise(['content' => $content, 'books' => $books])->renderWith('Book');
    }
}