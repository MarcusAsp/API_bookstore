<?php
// Retrieve the request's body and parse it as JSON:
$input = file_get_contents('php://input');

// Do something with $event_json
$file = 'charges/' . date("Ymd_His") . '.json';

// Write the contents back to the file
file_put_contents($file, $input);

// Return a response to acknowledge receipt of the event
http_response_code(200); // PHP 5.4 or greater
