<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/security.php';
require_once __DIR__ . '/../middleware/tokenvalidation.php';
require_once __DIR__ . '/../helper/logAction.php';

$request = [
    'Authorization' => getallheaders()['Authorization'] ?? null
];

$authUser = jwtMiddleware($request);

$category = $_GET['category'] ?? null;
$date = $_GET['date'] ?? null;

// Build dynamic query for optimized filtering
$db = (new Database())->getConnection();

$query = "SELECT id, amount, category, description, expense_date, created_at 
          FROM expenses WHERE user_id = :user_id";
$params = [':user_id' => $authUser['user']['userId']];

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
echo json_encode($expenses);
?>