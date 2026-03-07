<?php
session_start();
include 'db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = "กรุณากรอกอีเมลและรหัสผ่าน";
    } else {
        $stmt = mysqli_prepare($connection, "SELECT * FROM users WHERE email = ? LIMIT 1");

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);

            mysqli_stmt_close($stmt);

            $login_success = false;

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $login_success = true;
                } elseif ($password === $user['password']) {
                    // รองรับบัญชีเก่าที่รหัสผ่านยังเป็น plain text
                    $login_success = true;

                    // อัปเกรดรหัสผ่านเดิมเป็น hash ทันทีหลัง login สำเร็จ
                    $new_hash = password_hash($password, PASSWORD_DEFAULT);
                    $upgrade_stmt = mysqli_prepare($connection, "UPDATE users SET password = ? WHERE user_id = ?");

                    if ($upgrade_stmt) {
                        mysqli_stmt_bind_param($upgrade_stmt, "si", $new_hash, $user['user_id']);
                        mysqli_stmt_execute($upgrade_stmt);
                        mysqli_stmt_close($upgrade_stmt);
                    }
                }
            }

            if ($login_success) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = $user['user_type'];
                $_SESSION['user_name'] = $user['first_name'];

                if ($user['user_type'] === 'admin') {
                    header("Location: admin.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $error = "อีเมลหรือรหัสผ่านไม่ถูกต้อง!";
            }
        } else {
            $error = "เกิดข้อผิดพลาดของระบบ กรุณาลองใหม่อีกครั้ง";
        }
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
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        required
                        value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                    >
                </div>

                <div class="form-group">
                    <label>รหัสผ่าน</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn custom-btn btn-block mt-3">เข้าสู่ระบบ</button>

                <p class="mt-3 text-center">
                    ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a>
                </p>

                <?php if ($error !== ''): ?>
                    <p class="text-danger text-center"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
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