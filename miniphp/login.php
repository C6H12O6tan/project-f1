<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']); 

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($connection, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['user_type'];
        $_SESSION['user_name'] = $user['first_name'];

        if ($user['user_type'] == 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $error = "อีเมลหรือรหัสผ่านไม่ถูกต้อง!";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เข้าสู่ระบบ | F1 Ticket Management</title>
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
    <h2 class="text-center text-custom-red">เข้าสู่ระบบ</h2>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <form method="post">
                <div class="form-group">
                    <label>อีเมล</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>รหัสผ่าน</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn custom-btn btn-block mt-3">เข้าสู่ระบบ</button>
                <p class="mt-3 text-center">ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a></p>
                <?php if (isset($error)) { echo "<p class='text-danger text-center'>$error</p>"; } ?>
            </form>
        </div>
    </div>
</div>

<footer class="custom-footer text-center py-3 mt-5">
    <p>&copy; 2025 F1 Ticket Management | All Rights Reserved By ME</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
