<?php
function logAction($db, $userId, $expenseId, $actionType, $description) {
    $query = "
        INSERT INTO action_logs (user_id, expense_id, action_type, description, user_agent, os, ip_address) 
        VALUES (:user_id, :expense_id, :action_type, :description, :user_agent, :os, :ip_address)
    ";

    $stmt = $db->prepare($query);

    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $os = php_uname('s') . ' ' . php_uname('r');
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';

    $stmt->execute([
        ':user_id' => $userId,
        ':expense_id' => $expenseId ?: null, // Can be NULL
        ':action_type' => $actionType,
        ':description' => $description,
        ':user_agent' => $userAgent,
        ':os' => $os,
        ':ip_address' => $ipAddress,
    ]);
}

