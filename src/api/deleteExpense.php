<?php

// Importing files 
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/security.php';
require_once __DIR__ . '/../middleware/tokenvalidation.php';
require_once __DIR__ . '/../helper/logAction.php';

// Setting Header for Bearer Token
$request = [
    'Authorization' => getallheaders()['Authorization'] ?? null
];

$authUser = jwtMiddleware($request); // Middleware for authentication and role validation

// Getting the id from params
$expenseId = $_GET['id'] ?? null;

if (!$expenseId) {
    http_response_code(400);
    echo json_encode(["status" => false, "message" => "Expense ID is required."]);
    exit;
}


// DB connection object
$db = (new Database())->getConnection();

// Check permissions and delete the expense
$query = ($authUser['user']['role'] === 'admin')
    ? "DELETE FROM expenses WHERE id = :id"
    : "DELETE FROM expenses WHERE id = :id AND user_id = :user_id";

$stmt = $db->prepare($query);
$params = [':id' => sanitizeInput($expenseId)];

if ($authUser['user']['role'] !== 'admin') {
    $params[':user_id'] = $authUser['user']['userId'];
}

// Operation Performed
if ($stmt->execute($params)) {
    
    // Keeping logs of each operations 
    logAction($db, $authUser['user']['userId'], $expenseId, 'DELETE', 'Expense deleted');
    echo json_encode(["status" => true, "message" => "Expense deleted successfully."]);
} else {
    http_response_code(500);
    echo json_encode(["status" => false, "message" => "Failed to delete expense."]);
}
?>
