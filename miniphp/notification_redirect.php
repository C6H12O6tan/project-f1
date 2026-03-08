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

if ($notificationId <= 0) {
    header("Location: notifications.php");
    exit();
}

$sql = "
    SELECT notification_id, user_id, link
    FROM notifications
    WHERE notification_id = ? AND user_id = ?
    LIMIT 1
";

$stmt = mysqli_prepare($connection, $sql);

if (!$stmt) {
    header("Location: notifications.php");
    exit();
}

mysqli_stmt_bind_param($stmt, "ii", $notificationId, $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$notification = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$notification) {
    header("Location: notifications.php");
    exit();
}

$updateSql = "
    UPDATE notifications
    SET is_read = 1
    WHERE notification_id = ? AND user_id = ?
";

$updateStmt = mysqli_prepare($connection, $updateSql);

if ($updateStmt) {
    mysqli_stmt_bind_param($updateStmt, "ii", $notificationId, $userId);
    mysqli_stmt_execute($updateStmt);
    mysqli_stmt_close($updateStmt);
}

$redirectLink = !empty($notification['link']) ? $notification['link'] : 'notifications.php';

header("Location: " . $redirectLink);
exit();