<?php

// Import the validateJWT function
require_once __DIR__ . '/../utils/jwt.php';



function jwtMiddleware($request) {
    // Check if the Authorization header exists
    $authHeader = isset($request['Authorization']) ? $request['Authorization'] : null;
    
    if (!$authHeader) {
        // If no token is provided, return unauthorized error
        http_response_code(401);
        echo json_encode(["status" => false, "message" => "Authorization token is required."]);
        exit();
    }

    // Extract the token from the Authorization header (format: "Bearer token")
    $token = str_replace("Bearer ", "", $authHeader);

    if (!$token) {
        http_response_code(401);
        echo json_encode(["status" => false, "message" => "Token not provided."]);
        exit();
    }

    // Validate the token
    $decoded = validateJWT($token);  // Call the imported validateJWT function

    if ($decoded === null) {
        http_response_code(401);
        echo json_encode(["status" => false, "message" => "Invalid or expired token."]);
        exit();
    }

    // If token is valid, attach the decoded payload to the request object
    // You can use this user data for additional authorization checks (e.g., roles)
    $request['user'] = $decoded;

    return $request;
}
