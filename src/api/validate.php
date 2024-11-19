<?php

// Importing file
require_once __DIR__ . '/../middleware/tokenvalidation.php';

$request = [
    'Authorization' => getallheaders()['Authorization'] ?? null
];

// Passing the request through the middleware
$request = jwtMiddleware($request);

// If the token is valid, return a success response
http_response_code(200);
echo json_encode([
    "status" => true,
    "message" => "Token is valid!",
    "user" => $request['user'] // This contains the decoded token payload
]);