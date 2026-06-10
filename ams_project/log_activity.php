<?php

function logActivity($conn, $user_id, $username, $action)
{
    $page = $_SERVER['PHP_SELF'];

    $ip = $_SERVER['REMOTE_ADDR'];
    try {
    $stmt = mysqli_prepare($conn, "
        INSERT INTO activity_logs
        (user_id, username, action, page_name, ip_address)
        VALUES (?, ?, ?, ?, ?)
    ");
   
    mysqli_stmt_bind_param($stmt, "issss", $user_id, $username, $action, $page, $ip);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
        
} catch (Exception $e) {
    error_log("Failed to log activity: " . $e->getMessage());
}
}