<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone_number = $_POST['phone_number'];
    $user_type = $_POST['user_type'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (first_name, last_name, email, password, phone_number, user_type) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("ssssss", $first_name, $last_name, $email, $hashed_password, $phone_number, $user_type);

    if ($stmt->execute()) {
        header("Location: admin_manage_users.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เพิ่มผู้ใช้</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>เพิ่มผู้ใช้</h2>
    <form method="POST">
        <div class="form-group">
            <label>ชื่อ</label>
            <input type="text" name="first_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>นามสกุล</label>
            <input type="text" name="last_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>อีเมล</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>รหัสผ่าน</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label>เบอร์โทร</label>
            <input type="text" name="phone_number" class="form-control" required>
        </div>
        <div class="form-group">
            <label>ประเภทผู้ใช้</label>
            <select name="user_type" class="form-control">
                <option value="user">user</option>
                <option value="admin">admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">เพิ่มผู้ใช้</button>
        <a href="admin_manage_users.php" class="btn btn-secondary">กลับ</a>
    </form>
</div>
</body>
</html>
