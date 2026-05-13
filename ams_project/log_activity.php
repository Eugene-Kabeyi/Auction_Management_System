<?php

function logActivity($conn, $user_id, $username, $action)
{
    $page = $_SERVER['PHP_SELF'];

    $ip = $_SERVER['REMOTE_ADDR'];
    try {
    $stmt = $conn->prepare("
        INSERT INTO activity_logs
        (user_id, username, action, page_name, ip_address)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $user_id,
        $username,
        $action,
        $page,
        $ip
    ]);
} catch (PDOException $e) {
    error_log("Failed to log activity: " . $e->getMessage());
}
}