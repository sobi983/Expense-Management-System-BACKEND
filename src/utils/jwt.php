<?php
// Include the Composer autoloader
require_once __DIR__ . '/../../vendor/autoload.php';

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

function generateJWT($userId, $username) {
    //keys permission chmod 600
    $key = "4be3967d61d675abe1673cc5afa179bcb088d43219ecc09a8f91d1bd21a4b7f23932a7dfded18ea738d37a6b6809954087a196bf884c256097c9276dd67c58c898a849bf105800c47679dfb89522d8d2f79d20bc3ebb97890537c97827f610aa2c13e20cdfe1a86373a907f0dd2c83ae5d2e5c98142408fd7e4d3c1b724b2c73"; // Secret key to encode the JWT token
    $issuedAt = time();
    $expirationTime = $issuedAt + 3600;  // jwt valid for 1 hour from the issued time
    $payload = array(
        "userId" => $userId,
        "username" => $username,
        "iat" => $issuedAt,
        "exp" => $expirationTime
    );
    return JWT::encode($payload, $key, 'HS256');
}

function validateJWT($token) {
    //keys permission chmod 644
    $key = "4be3967d61d675abe1673cc5afa179bcb088d43219ecc09a8f91d1bd21a4b7f23932a7dfded18ea738d37a6b6809954087a196bf884c256097c9276dd67c58c898a849bf105800c47679dfb89522d8d2f79d20bc3ebb97890537c97827f610aa2c13e20cdfe1a86373a907f0dd2c83ae5d2e5c98142408fd7e4d3c1b724b2c73"; // The secret key used for encoding and decoding
    try {
        // Use the new `Key` object as per the library's updated signature
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        return (array) $decoded; // Return the decoded token as an associative array
    } catch (Exception $e) {
        // Log or handle the exception if necessary
        return null; // Return null if validation fails
    }
}
?>
