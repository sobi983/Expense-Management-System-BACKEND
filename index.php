<?php
// Define the base route
$baseRoute = '/api/v1';

// Defining the response type
header('Content-Type: application/json; charset=utf-8');

// Get the current request URI and parse the path and query
$requestUri = $_SERVER['REQUEST_URI'];
$requestPath = parse_url($requestUri, PHP_URL_PATH);
$queryString = parse_url($requestUri, PHP_URL_QUERY);

// Initialize query parameters array
$queryParams = [];
if (!is_null($queryString)) {
    parse_str($queryString, $queryParams);
}

// Debugging to check the incoming request
// Uncomment to debug:
// error_log("Request URI: $requestUri");
// error_log("Request Path: $requestPath");
// error_log("Query Parameters: " . print_r($queryParams, true));

// Trim the base route to extract the endpoint
if (strpos($requestPath, $baseRoute) === 0) {
    // Extract the endpoint from the path
    $endpoint = substr($requestPath, strlen($baseRoute));

    // Route based on the endpoint
    switch ($endpoint) {
        case '/signup':
            require 'src/api/signup.php';
            break;
        case '/signin':
            require 'src/api/signin.php';
            break;
        case '/createExpense':
            require 'src/api/createExpense.php';
            break;
        case '/getExpense':
            require 'src/api/getExpense.php';
            break;
        case '/getAllExpenses':
            require 'src/api/getAllExpenses.php';
            break;
        case '/editExpense':
            // Ensure 'id' is present in query params
            if (!isset($queryParams['id']) || !is_numeric($queryParams['id'])) {
                http_response_code(400);
                echo json_encode(["message" => "Missing or invalid 'id' parameter."]);
                exit;
            }

            // Pass 'id' to the editExpense script
            $_GET['id'] = $queryParams['id'];
            require 'src/api/editExpense.php';
            break;
        case '/deleteExpense':
            // Ensure 'id' is present in query params
            if (!isset($queryParams['id']) || !is_numeric($queryParams['id'])) {
                http_response_code(400);
                echo json_encode(["message" => "Missing or invalid 'id' parameter."]);
                exit;
            }

            // Pass 'id' to the editExpense script
            $_GET['id'] = $queryParams['id'];
            require 'src/api/deleteExpense.php';
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
