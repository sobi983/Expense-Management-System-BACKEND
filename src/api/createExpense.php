<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/security.php';
require_once __DIR__ . '/../middleware/tokenvalidation.php';
require_once __DIR__ . '/../helper/logAction.php';

$request = [
    'Authorization' => getallheaders()['Authorization'] ?? null
];

$authUser = jwtMiddleware($request); // Middleware for authentication and role validation

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data['amount']) || empty($data['category']) || empty($data['expense_date'])) {
    http_response_code(400);
    echo json_encode(["message" => "Invalid input."]);
    exit;
}

// Prepare and execute optimized query
$db = (new Database())->getConnection();

$query = "INSERT INTO expenses (user_id, amount, category, description, expense_date) 
          VALUES (:user_id, :amount, :category, :description, :expense_date)";
$stmt = $db->prepare($query);

$amount = sanitizeInput($data['amount']);
$category = sanitizeInput($data['category']);
$description = sanitizeInput($data['description']);
$expense_date = sanitizeInput($data['expense_date']);

$stmt->execute([
    ':user_id' => $authUser['user']['userId'],
    ':amount' => $amount,
    ':category' => $category,
    ':description' => $description ?? null,
    ':expense_date' => $expense_date
]);

logAction($db, $authUser['user']['userId'], null, 'CREATE', 'Expense created'); // Log action
echo json_encode(["message" => "Expense created successfully."]);
?>
