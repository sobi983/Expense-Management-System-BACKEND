<?php

// Importing files 
require_once __DIR__ . '/../config/database.php'; 
require_once __DIR__ . '/../utils/jwt.php'; 
require_once __DIR__ . '/../utils/security.php';

// Converted into the object
$data = json_decode(file_get_contents("php://input"));

// Validate the input data
if (empty($data->email) || empty($data->password)) {
    http_response_code(400);
    echo json_encode(["status" => false, "message" => "Please provide email and password"]);
    exit;
}

// DB connection object
$db = (new Database())->getConnection();

// Sanitize input values
$email = sanitizeInput($data->email);
$password = sanitizeInput($data->password);

// Find the user in the database
$query = "SELECT id, username, password, role FROM users WHERE email = :email";
$stmt = $db->prepare($query);
$stmt->bindParam(':email', $email);
$stmt->execute();

if ($stmt->rowCount() === 0) {
    http_response_code(401);
    echo json_encode(["status" => false, "message" => "Invalid email or password"]);
    exit;
}

// Fetching the data from DB
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Verify the password
if (!password_verify($password, $user['password'])) {
    http_response_code(401);
    echo json_encode(["status" => false, "message" => "Invalid email or password"]);
    exit;
}

// Generate a JWT token for the session
$token = generateJWT($user['id'], $user['username'], $user['role']);

// Return the token and user details
echo json_encode([
    "status" => true, 
    "message" => "Login successful",
    "token" => $token,
    "user" => [
        "id" => $user['id'],
        "username" => $user['username'],
        "role" => $user['role']
    ]
]);
?>
