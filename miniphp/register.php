<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($connection, $_POST['last_name']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']); 
    $phone_number = mysqli_real_escape_string($connection, $_POST['phone_number']);

    if (!is_numeric($password) || strlen($password) < 8) {
        echo "รหัสผ่านต้องเป็นตัวเลขและมีความยาวอย่างน้อย 8 ตัวอักษร";
        exit();
    }

    $query = "INSERT INTO users (first_name, last_name, email, password, phone_number, user_type)
              VALUES ('$first_name', '$last_name', '$email', '$password', '$phone_number', 'user')";

    if (mysqli_query($connection, $query)) {
        header("Location: login.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาด: " . mysqli_error($connection);
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สมัครสมาชิก | F1 Ticket Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>

<nav class="navbar navbar-expand-lg custom-navbar">
    <div class="container">
        <a class="navbar-brand" href="index.php">F1 Ticket Management</a>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center text-custom-red">สมัครสมาชิก</h2>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <form method="post">
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
                    <label>เบอร์โทรศัพท์</label>
                    <input type="text" name="phone_number" class="form-control" required>
                </div>
                <button type="submit" class="btn custom-btn btn-block mt-3">สมัครสมาชิก</button>
                <p class="mt-3 text-center">มีบัญชีอยู่แล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
            </form>
        </div>
    </div>
</div>

<footer class="custom-footer text-center py-3 mt-5">
    <p>&copy; 2025 F1 Ticket Management | All Rights Reserved By ME</p>
</footer>

</body>
</html>
