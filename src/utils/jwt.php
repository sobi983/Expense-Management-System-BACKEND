<?php
// Include the Composer autoloader
require_once __DIR__ . '/../../vendor/autoload.php';

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

function generateJWT($userId, $username, $role) {
    //keys permission chmod 600 for file directory
    $key = file_get_contents('/var/www/html/keys/private.key'); // Secret key to encode the JWT token
    $issuedAt = time();
    $expirationTime = $issuedAt + 3600;  // jwt valid for 1 hour from the issued time
    $payload = [
        "userId" => $userId,
        "username" => $username,
        "role" => $role,
        "iat" => $issuedAt,
        "exp" => $expirationTime
    ];
    return JWT::encode($payload, $key, 'RS256');
}

function validateJWT($token) {
    //keys permission chmod 644
    $key = file_get_contents('/var/www/html/keys/public.key'); // The secret key used for encoding and decoding
    try {
       
        $decoded = JWT::decode($token, new Key($key, 'RS256'));
        return (array) $decoded; // Return the decoded token as an associative array
    } catch (Exception $e) {
        // Log or handle the exception if necessary
        return null; // Return null if validation fails
    }
}
?>
