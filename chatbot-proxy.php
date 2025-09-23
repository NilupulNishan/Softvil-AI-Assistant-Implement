<?php

// 1. These HTTP headers handle CORS (Cross-Origin Resource Sharing) and set the response format to JSON.
header("Access-Control-Allow-Origin: *"); // allows requests from any website
header("Access-Control-Allow-Headers: Content-Type"); // lets the frontend send JSON.
header("Content-Type: application/json"); // tells the browser that the PHP script will return JSON.

// 2. This ensures only POST requests are allowed
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method Not Allowed"]);
    exit;
}

// 3. Reads the raw request body (the JSON you sent from your JavaScript 'fetch'
$input = file_get_contents('php://input');


$apiKey = "app-sFWO6LFJougo4Zy08h4STjSs";

$apiUrl = "https://api.dify.ai/v1/chat-messages";


//  4. Basically: whatever JSON you sent from JS, this forwards it to Dify with your API key
$ch = curl_init($apiUrl); //start a request to Dify’s API.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //tells cURL to return the response as a string instead of outputting it directly.
// adds headers (Content-Type JSON, Authorization: Bearer <your key>).
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey"
]);

curl_setopt($ch, CURLOPT_POST, true); // makes it a POST request.
curl_setopt($ch, CURLOPT_POSTFIELDS, $input); // attaches your $input (the JSON body from frontend).

// Executes the cURL request.
$response = curl_exec($ch); // = the JSON reply from Dify (e.g., { "answer": "Hello!" }).
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE); // = HTTP status code from Dify (200, 400, etc.).
curl_close($ch); // Closes the cURL handle.

// Returns the same status code and response JSON back to your frontend.
http_response_code($status);
echo $response;