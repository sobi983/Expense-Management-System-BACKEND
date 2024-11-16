<?php

require_once __DIR__ . '/../middleware/tokenvalidation.php';

$request = [
    'Authorization' => getallheaders()['Authorization'] ?? null
];

// Pass the request through the middleware
$request = jwtMiddleware($request);

// If the token is valid, return a success response
http_response_code(200);
echo json_encode([
    "message" => "Token is valid!",
    "user" => $request['user'] // This contains the decoded token payload
]);