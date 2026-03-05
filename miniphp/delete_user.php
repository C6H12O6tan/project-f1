<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    $check_query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $connection->prepare($check_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $delete_query = "DELETE FROM users WHERE user_id = ?";
        $stmt = $connection->prepare($delete_query);
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            header("Location: admin_manage_users.php");
            exit();
        } else {
            echo "เกิดข้อผิดพลาดในการลบผู้ใช้: " . $stmt->error;
        }
    } else {
        echo "<script>alert('ไม่พบข้อมูลผู้ใช้!'); window.location.href='admin_manage_users.php';</script>";
    }
} else {
    echo "<script>alert('ไม่มีการระบุ User ID!'); window.location.href='admin_manage_users.php';</script>";
}
?>
