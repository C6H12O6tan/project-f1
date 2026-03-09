<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit();
}

$userId = isset($_GET['user_id']) ? (int) $_GET['user_id'] : 0;
$currentAdminId = (int) $_SESSION['user_id'];

if ($userId <= 0) {
    echo "<script>alert('ไม่มีการระบุ User ID!'); window.location.href='admin_manage_users.php';</script>";
    exit();
}

/* ป้องกัน admin ลบตัวเอง */
if ($userId === $currentAdminId) {
    echo "<script>alert('ไม่สามารถลบบัญชีของตัวเองได้'); window.location.href='admin_manage_users.php';</script>";
    exit();
}

/* ตรวจสอบว่ามี user จริงหรือไม่ */
$checkSql = "SELECT user_id, first_name, last_name, role FROM users WHERE user_id = ? LIMIT 1";
$checkStmt = mysqli_prepare($connection, $checkSql);

if (!$checkStmt) {
    echo "<script>alert('เกิดข้อผิดพลาดในการตรวจสอบข้อมูลผู้ใช้'); window.location.href='admin_manage_users.php';</script>";
    exit();
}

mysqli_stmt_bind_param($checkStmt, "i", $userId);
mysqli_stmt_execute($checkStmt);
$result = mysqli_stmt_get_result($checkStmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($checkStmt);

if (!$user) {
    echo "<script>alert('ไม่พบข้อมูลผู้ใช้!'); window.location.href='admin_manage_users.php';</script>";
    exit();
}

/*
   เช็กข้อมูลที่เชื่อมอยู่
   ถ้ามี bookings หรือ notifications อยู่ แนะนำไม่ลบจริง
   เพื่อกันข้อมูลพังในระบบ
*/
$bookingCount = 0;
$notificationCount = 0;

/* เช็ก bookings */
$bookingSql = "SELECT COUNT(*) AS total FROM bookings WHERE userid = ?";
$bookingStmt = mysqli_prepare($connection, $bookingSql);

if ($bookingStmt) {
    mysqli_stmt_bind_param($bookingStmt, "i", $userId);
    mysqli_stmt_execute($bookingStmt);
    $bookingResult = mysqli_stmt_get_result($bookingStmt);
    $bookingRow = mysqli_fetch_assoc($bookingResult);
    $bookingCount = (int) ($bookingRow['total'] ?? 0);
    mysqli_stmt_close($bookingStmt);
}

/* เช็ก notifications */
$notificationSql = "SELECT COUNT(*) AS total FROM notifications WHERE user_id = ?";
$notificationStmt = mysqli_prepare($connection, $notificationSql);

if ($notificationStmt) {
    mysqli_stmt_bind_param($notificationStmt, "i", $userId);
    mysqli_stmt_execute($notificationStmt);
    $notificationResult = mysqli_stmt_get_result($notificationStmt);
    $notificationRow = mysqli_fetch_assoc($notificationResult);
    $notificationCount = (int) ($notificationRow['total'] ?? 0);
    mysqli_stmt_close($notificationStmt);
}

/* ถ้ามี booking อยู่ ไม่ควรลบ user ตรง ๆ */
if ($bookingCount > 0) {
    echo "<script>alert('ไม่สามารถลบผู้ใช้นี้ได้ เพราะมีประวัติการจองอยู่ในระบบ'); window.location.href='admin_manage_users.php';</script>";
    exit();
}

mysqli_begin_transaction($connection);

try {
    /* ลบ notifications ก่อน */
    if ($notificationCount > 0) {
        $deleteNotificationsSql = "DELETE FROM notifications WHERE user_id = ?";
        $deleteNotificationsStmt = mysqli_prepare($connection, $deleteNotificationsSql);

        if (!$deleteNotificationsStmt) {
            throw new Exception('prepare delete notifications failed');
        }

        mysqli_stmt_bind_param($deleteNotificationsStmt, "i", $userId);

        if (!mysqli_stmt_execute($deleteNotificationsStmt)) {
            mysqli_stmt_close($deleteNotificationsStmt);
            throw new Exception('execute delete notifications failed');
        }

        mysqli_stmt_close($deleteNotificationsStmt);
    }

    /* ลบ user */
    $deleteUserSql = "DELETE FROM users WHERE user_id = ? LIMIT 1";
    $deleteUserStmt = mysqli_prepare($connection, $deleteUserSql);

    if (!$deleteUserStmt) {
        throw new Exception('prepare delete user failed');
    }

    mysqli_stmt_bind_param($deleteUserStmt, "i", $userId);

    if (!mysqli_stmt_execute($deleteUserStmt)) {
        mysqli_stmt_close($deleteUserStmt);
        throw new Exception('execute delete user failed');
    }

    mysqli_stmt_close($deleteUserStmt);

    mysqli_commit($connection);

    echo "<script>alert('ลบผู้ใช้สำเร็จแล้ว'); window.location.href='admin_manage_users.php';</script>";
    exit();
} catch (Exception $e) {
    mysqli_rollback($connection);
    echo "<script>alert('เกิดข้อผิดพลาดในการลบผู้ใช้'); window.location.href='admin_manage_users.php';</script>";
    exit();
}
?>