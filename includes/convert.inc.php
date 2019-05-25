<?php
require_once('./db/db.php');
class Convert
{
    private $writeFile = 'downloadFile.csv';
    private $db;
    protected $books = [];

    public function __construct()
    {
        $this->db = new Dbh();
        $this->db = $this->db->connect();
        $books[] = ['ISBN', 'Book title', 'Author'];
    }

    public function getBooks($filename){
        if ($file_handle = fopen($filename, 'r')) {
            // Read one line from the csv file, use comma as separator
            while ($data = fgetcsv($file_handle)) {
                $books[] = $this->fill_book($data[0]);
            }
            fclose($file_handle);
            return $books;
        }
    }


// The nested array to hold all the arrays

// Open the file for reading

// Display the code in a readable format
//var_dump($books);
/*
if ($books) {
    $filename = 'downloadFile.csv';
    $file_to_write = fopen($filename, 'w');
    $everything_is_awesome = true;
    foreach ($books as $book) {
        //$book = fill_book($book[0]);
        //var_dump($book);
        $everything_is_awesome = $everything_is_awesome && fputcsv($file_to_write, $book);
    }
    fclose($file_to_write);
    if ($everything_is_awesome) {
        echo '<a href="' . $filename . '">Everything is awesome</a>';
    } else {
        echo 'Everything is NOT awesome';
    }
}
*/
    public function fill_book($isbn) 
    {
        $book = [];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://postman.stellasinawebb.se/api/book_api/read_single.php?ISBN=".$isbn."&apiKey=5cdc665eac26c&fbclid=IwAR3P8twqAaRSr-pgGcrYbGtBlTfpDF5y-4CVfL8ZJr46GccrZr1Ii5WMiS4");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $bookInfo = curl_exec($ch);
        curl_close($ch);
        
        $answer = explode('{', $bookInfo);
        $answer = "{".$answer[1];
        $answer = json_decode($answer, true);
       if($answer['ISBN'] != "null"){
        
            $book[0] = $isbn;
            $book[1] = $answer['bookTitle'];
            $book[2] = $answer['authorName'];
            $book[3] = $answer['publisherName'];
            return $book;
        }else{
            return;
        }
    }
}