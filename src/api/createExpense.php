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

// Conditions 
if (!$data || empty($data['amount']) || empty($data['category']) || empty($data['expense_date'])) {
    http_response_code(400);
    echo json_encode(["status" => false, "message" => "Invalid input."]);
    exit;
}

// DB connection object
$db = (new Database())->getConnection();

// Prepare and execute optimized query
$query = "INSERT INTO expenses (user_id, amount, category, description, expense_date) 
          VALUES (:user_id, :amount, :category, :description, :expense_date)";
$stmt = $db->prepare($query);

$amount = sanitizeInput($data['amount']);
$category = sanitizeInput($data['category']);
$description = sanitizeInput($data['description']);
$expense_date = sanitizeInput($data['expense_date']);

// Inserting the data
try {
    $stmt->execute([
        ':user_id' => $authUser['user']['userId'],
        ':amount' => $amount,
        ':category' => $category,
        ':description' => $description ?? null,
        ':expense_date' => $expense_date
    ]);

    // Fetch the ID of the newly created expense
    $newExpenseId = $db->lastInsertId();

    // Query 
    $fetchQuery = "SELECT id, user_id, amount, category, description, expense_date 
                   FROM expenses WHERE id = :id";
    $fetchStmt = $db->prepare($fetchQuery);
    $fetchStmt->execute([':id' => $newExpenseId]);

    // Fetched the records
    $newExpense = $fetchStmt->fetch(PDO::FETCH_ASSOC);

    if ($newExpense) {
        // Keeping logs of each operations 
        logAction($db, $authUser['user']['userId'], null, 'CREATE', 'Expense created'); // Log action
        echo json_encode(["status" => true, "message" => "Saved Sucessfully", "data" => $newExpense]);
    } else {
        throw new Exception("Failed to fetch the newly created expense.");
    }

} catch (Exception $e) {  // Error Handling
    http_response_code(500);
    echo json_encode(["status" => false, "message" => "Error saving expense.", "error" => $e->getMessage()]);
}
?>
