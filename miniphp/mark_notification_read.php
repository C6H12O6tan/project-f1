<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = (int) $_SESSION['user_id'];
$notificationId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$mode = $_GET['mode'] ?? '';

if ($mode === 'all') {
    $sql = "
        UPDATE notifications
        SET is_read = 1
        WHERE user_id = ?
    ";

    $stmt = mysqli_prepare($connection, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    header("Location: notifications.php");
    exit();
}

if ($notificationId > 0) {
    $sql = "
        UPDATE notifications
        SET is_read = 1
        WHERE notification_id = ? AND user_id = ?
    ";

    $stmt = mysqli_prepare($connection, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $notificationId, $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

header("Location: notifications.php");
exit();