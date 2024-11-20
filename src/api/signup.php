<?php

// Importing files 
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/security.php';

$data = json_decode(file_get_contents("php://input"));

// Validate the input data
if (empty($data->username) || empty($data->email) || empty($data->password)) {
    http_response_code(200);
    echo json_encode(["status" => false, "message" => "Please provide username, email, and password"]);
    exit;
}

// Validate invalid emails
if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(200);
    echo json_encode(["status" => false, "message" => "Invalid email format."]);
    exit;
}

// DB connection object
$db = (new Database())->getConnection();

// Sanitize input values
$email = sanitizeInput($data->email);
$username = sanitizeInput($data->username);
$password = sanitizeInput($data->password);
$role = isset($data->role) && $data->role === 'admin' ? 'admin' : 'user';

// Check if the user already exists
$query = "SELECT id FROM users WHERE email = :email OR username = :username";
$stmt = $db->prepare($query);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':username', $username);
$stmt->execute();

// If more than or equal to 1 data fround then the user exists
if ($stmt->rowCount() > 0) {
    http_response_code(200);
    echo json_encode(["status" => false, "message" => "User with this email or username already exists"]);
    exit;
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert the new user into the database
$query = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";
$stmt = $db->prepare($query);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':password', $hashed_password);
$stmt->bindParam(':role', $role);

if ($stmt->execute()) {
    echo json_encode(["status" => true, "message" => "User registered successfully"]);
} else {
    http_response_code(500);
    echo json_encode(["status" => false, "message" => "Failed to register user"]);
}
