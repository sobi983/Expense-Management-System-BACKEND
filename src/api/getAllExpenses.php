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

// Admin role only
if ($authUser['user']['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(["status" => false, "message" => "Access denied."]);
    exit;
}

$category = $_GET['category'] ?? null;
$date = $_GET['date'] ?? null;

// DB connection object
$db = (new Database())->getConnection();

// Dynamic query for admin filtering
$query = "SELECT id, user_id, amount, category, description, expense_date, created_at FROM expenses WHERE 1=1";
$params = [];

if ($category) {
    $query .= " AND category = :category";
    $params[':category'] = $category;
}
if ($date) {
    $query .= " AND expense_date = :date";
    $params[':date'] = $date;
}

$stmt = $db->prepare($query);
$stmt->execute($params); // Query executed

// Fetch all records from theb table
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(["status" => true, "message" => $expenses]);
?>
