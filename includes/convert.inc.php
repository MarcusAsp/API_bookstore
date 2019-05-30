<?php
// Includes the database class
require_once('./db/db.php');
class Convert
{
    private $writeFile = 'downloadFile.csv';
    private $db;
    protected $books = [];
    public $booksNotFound = [];

    // Sets up a connection to the database and adds some items to the books array
    public function __construct()
    {
        $this->db = new Dbh();
        $this->db = $this->db->connect();
        $this->books[] = ['ISBN', 'Book title', 'Author', "Publisher"];
    }
    // The function takes the uploaded file as the parameter
    public function getBooks($filename)
    {
        // if the file is readable do something
        if ($file_handle = fopen($filename, 'r')) {
            // reads the rows from the file for every item in the array, send the content to the "fill_book" function.
            // and then puts the response in to an array called readBook.
            while ($data = fgetcsv($file_handle)) {
                $readBook = $this->fill_book($data[0]);
                // If the values is in an array format, set the readBooks array to the general books array and then closes the file and returns the general books array
                if (is_array($readBook)) {
                    $this->books[] = $readBook;
                }
            }
            fclose($file_handle);
            return $this->books;
        }
    }

    /* 
    The fill_book function takes in an isbn number that is set from the uploaded file.
    casts a curl to the selected website(api) with the current isbn number and returns the answer.
    Explodes out the "var_dump" that is set and then decodes the json to a valid array.

    if the isbn number has a null response from the curl. Then insert the isbn number to the booksNotFound variable.
    If not, return the information to a variable called book and return it to the main function.

    */
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
