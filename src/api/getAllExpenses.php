<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/security.php';
require_once __DIR__ . '/../middleware/tokenvalidation.php';
require_once __DIR__ . '/../helper/logAction.php';

$request = [
    'Authorization' => getallheaders()['Authorization'] ?? null
];

$authUser = jwtMiddleware($request);

if ($authUser['user']['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(["status" => false, "message" => "Access denied."]);
    exit;
}

$category = $_GET['category'] ?? null;
$date = $_GET['date'] ?? null;

// Dynamic query for admin filtering
$db = (new Database())->getConnection();

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
$stmt->execute($params);

$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(["status" => true, "message" => $expenses]);
?>
