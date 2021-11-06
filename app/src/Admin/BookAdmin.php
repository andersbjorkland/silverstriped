<?php

namespace App\Admin;

use App\Model\Book;
use SilverStripe\Admin\ModelAdmin;

class BookAdmin extends ModelAdmin
{
    private static $managed_models = [
        Book::class,
    ];

    private static $url_segment = 'books';

    private static $menu_title = 'Books';
}