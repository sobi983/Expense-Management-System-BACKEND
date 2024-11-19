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

$data = json_decode(file_get_contents("php://input"), true);
$expenseId = $_GET['id'] ?? null;

// Conditions 
if (!$expenseId || !$data || empty($data['amount']) || empty($data['category']) || empty($data['expense_date'])) {
    http_response_code(400);
    echo json_encode(["status" => false, "message" => "Invalid input."]);
    exit;
}

// DB connection object
$db = (new Database())->getConnection();

// Check if the expense belongs to the user or if the role is admin
$query = ($authUser['user']['role'] === 'admin')
    ? "UPDATE expenses SET amount = :amount, category = :category, description = :description, expense_date = :expense_date WHERE id = :id"
    : "UPDATE expenses SET amount = :amount, category = :category, description = :description, expense_date = :expense_date 
       WHERE id = :id AND user_id = :user_id";

$stmt = $db->prepare($query);

$amount = sanitizeInput($data['amount']);
$category = sanitizeInput($data['category']);
$description = sanitizeInput($data['description']);
$expense_date = sanitizeInput($data['expense_date']);

$params = [
    ':id' => $expenseId,
    ':amount' => $amount,
    ':category' => $category,
    ':description' => $description ?? null,
    ':expense_date' => $expense_date
];

if ($authUser['user']['role'] !== 'admin') {
    $params[':user_id'] = $authUser['user']['userId'];
}

// Executed the query
if ($stmt->execute($params)) {
    // Keeping logs of each operations 
    logAction($db, $authUser['user']['userId'], $expenseId, 'UPDATE', 'Expense updated');
    echo json_encode(["status" => true, "message" => "Expense updated successfully."]);
} else {
    http_response_code(500);
    echo json_encode(["status" => false, "message" => "Failed to update expense."]);
}
?>
