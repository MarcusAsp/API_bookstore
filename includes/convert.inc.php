<?php
require_once('./db/db.php');
class Convert
{
    private $writeFile = 'downloadFile.csv';
    private $db;
    protected $books = [];
    public $booksNotFound = [];
    public function __construct()
    {
        $this->db = new Dbh();
        $this->db = $this->db->connect();
        $this->books[] = ['ISBN', 'Book title', 'Author', "Publisher"];
    }

    public function getBooks($filename)
    {
        if ($file_handle = fopen($filename, 'r')) {
            // Read one line from the csv file, use comma as separator
            while ($data = fgetcsv($file_handle)) {
                $readBook = $this->fill_book($data[0]);
                if (is_array($readBook)) {
                    $this->books[] = $readBook;
                }
            }
            fclose($file_handle);
            return $this->books;
        }
    }

    public function fill_book($isbn)
    {
        $book = [];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://postman.stellasinawebb.se/api/book_api/read_single.php?ISBN=" . $isbn . "&apiKey=5cdc665eac26c&fbclid=IwAR3P8twqAaRSr-pgGcrYbGtBlTfpDF5y-4CVfL8ZJr46GccrZr1Ii5WMiS4");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $bookInfo = curl_exec($ch);
        curl_close($ch);

        $answer = explode('{', $bookInfo);
        $answer = "{" . $answer[1];
        $answer = json_decode($answer, true);
        if (!is_null($answer['ISBN'])) {
            $book[0] = $answer['ISBN'];
            $book[1] = $answer['bookTitle'];
            $book[2] = $answer['authorName'];
            $book[3] = $answer['publisherName'];
            return $book;
        } else {
            $this->booksNotFound[] = $isbn;
        }
    }
}
