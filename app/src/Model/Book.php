<?php

namespace App\Model;

use SilverStripe\ORM\DataObject;

class Book extends DataObject
{
    private static $table_name = 'Book';

    private static $db = [
        'title' => 'Varchar(255)',
        'isbn13' => 'Varchar(13)',
        'isbn10' => 'Varchar(10)',
        // 'author' => 'Varchar(255)',
        'year' => 'Int',
        'pages' => 'Int',
        'description' => 'Text',
        'google_id' => 'Varchar(255)'
        // 'image' => 'Varchar(255)',
        // 'category' => 'Varchar(255)',
    ];
}