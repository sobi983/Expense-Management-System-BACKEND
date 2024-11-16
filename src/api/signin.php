<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/jwt.php';
require_once __DIR__ . '/../utils/security.php';

$data = json_decode(file_get_contents("php://input"));

// Validate the input data
if (empty($data->email) || empty($data->password)) {
    http_response_code(400);
    echo json_encode(["message" => "Please provide email and password"]);
    exit;
}

$db = (new Database())->getConnection();

// Sanitize input values
$email = sanitizeInput($data->email);
$password = sanitizeInput($data->password);

// Check if the user exists
$query = "SELECT id, username, password FROM users WHERE email = :email";
$stmt = $db->prepare($query);
$stmt->bindParam(':email', $email);  // Bind the sanitized email
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(401);
    echo json_encode(["message" => "User not found"]);
    exit;
}

// Verify the password
if (!password_verify($password, $user['password'])) {  // Verify the sanitized password
    http_response_code(401);
    echo json_encode(["message" => "Invalid password"]);
    exit;
}

// Generate JWT token
$token = generateJWT($user['id'], $user['username']);

echo json_encode(["message" => "Signin successful", "token" => $token]);

