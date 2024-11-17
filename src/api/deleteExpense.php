<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/security.php';
require_once __DIR__ . '/../middleware/tokenvalidation.php';
require_once __DIR__ . '/../helper/logAction.php';

$request = [
    'Authorization' => getallheaders()['Authorization'] ?? null
];

$authUser = jwtMiddleware($request);

$expenseId = $_GET['id'] ?? null;
if (!$expenseId) {
    http_response_code(400);
    echo json_encode(["message" => "Expense ID is required."]);
    exit;
}

// Check permissions and delete the expense
$db = (new Database())->getConnection();

$query = ($authUser['user']['role'] === 'admin')
    ? "DELETE FROM expenses WHERE id = :id"
    : "DELETE FROM expenses WHERE id = :id AND user_id = :user_id";

$stmt = $db->prepare($query);
$params = [':id' => sanitizeInput($expenseId)];

if ($authUser['user']['role'] !== 'admin') {
    $params[':user_id'] = $authUser['user']['userId'];
}

if ($stmt->execute($params)) {
    logAction($db, $authUser['user']['userId'], $expenseId, 'DELETE', 'Expense deleted');
    echo json_encode(["message" => "Expense deleted successfully."]);
} else {
    http_response_code(500);
    echo json_encode(["message" => "Failed to delete expense."]);
}
?>
