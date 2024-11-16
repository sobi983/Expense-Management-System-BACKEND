<?php
// Define the base route
$baseRoute = '/api/v1';

// Get the current request URI
$requestUri = $_SERVER['REQUEST_URI'];

// Trim the base route to check if the request matches
if (strpos($requestUri, $baseRoute) === 0) {
    // Extract the endpoint from the URI
    $endpoint = substr($requestUri, strlen($baseRoute));

    // Check the endpoint and include the corresponding file
    switch ($endpoint) {
        case '/signup':
            require 'src/api/signup.php';
            break;
        case '/signin':
            require 'src/api/signin.php';
            break;
        case '/validate':
            require 'src/api/validate.php';
            break;

       
        default:
            http_response_code(404);
            echo json_encode(["message" => "Endpoint not found"]);
            break;
    }
} else {
    // Base route not matched
    http_response_code(404);
    echo json_encode(["message" => "Base route not found"]);
}

?>
