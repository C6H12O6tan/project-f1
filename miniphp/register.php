<?php
session_start();
include 'db.php';

$error = '';
$success = '';

$first_name = '';
$last_name = '';
$email = '';
$phone_number = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $phone_number = trim($_POST['phone_number'] ?? '');

    if ($first_name === '' || $last_name === '' || $email === '' || $password === '' || $phone_number === '') {
        $error = 'กรุณากรอกข้อมูลให้ครบถ้วน';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'รูปแบบอีเมลไม่ถูกต้อง';
    } elseif (!is_numeric($password) || strlen($password) < 8) {
        $error = 'รหัสผ่านต้องเป็นตัวเลขและมีความยาวอย่างน้อย 8 หลัก';
    } else {
        $check_stmt = mysqli_prepare($connection, "SELECT user_id FROM users WHERE email = ? LIMIT 1");

        if ($check_stmt) {
            mysqli_stmt_bind_param($check_stmt, "s", $email);
            mysqli_stmt_execute($check_stmt);
            $check_result = mysqli_stmt_get_result($check_stmt);
            $existing_user = mysqli_fetch_assoc($check_result);
            mysqli_stmt_close($check_stmt);

            if ($existing_user) {
                $error = 'อีเมลนี้ถูกใช้งานแล้ว';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $insert_stmt = mysqli_prepare(
                    $connection,
                    "INSERT INTO users (first_name, last_name, email, password, phone_number, user_type)
                     VALUES (?, ?, ?, ?, ?, 'user')"
                );

                if ($insert_stmt) {
                    mysqli_stmt_bind_param(
                        $insert_stmt,
                        "sssss",
                        $first_name,
                        $last_name,
                        $email,
                        $hashed_password,
                        $phone_number
                    );

                    if (mysqli_stmt_execute($insert_stmt)) {
                        mysqli_stmt_close($insert_stmt);
                        header("Location: login.php");
                        exit();
                    } else {
                        $error = 'เกิดข้อผิดพลาดในการสมัครสมาชิก';
                    }

                    mysqli_stmt_close($insert_stmt);
                } else {
                    $error = 'เกิดข้อผิดพลาดของระบบ กรุณาลองใหม่อีกครั้ง';
                }
            }
        } else {
            $error = 'เกิดข้อผิดพลาดของระบบ กรุณาลองใหม่อีกครั้ง';
        }
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
                    <input
                        type="text"
                        name="first_name"
                        class="form-control"
                        required
                        value="<?php echo htmlspecialchars($first_name); ?>"
                    >
                </div>

                <div class="form-group">
                    <label>นามสกุล</label>
                    <input
                        type="text"
                        name="last_name"
                        class="form-control"
                        required
                        value="<?php echo htmlspecialchars($last_name); ?>"
                    >
                </div>

                <div class="form-group">
                    <label>อีเมล</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        required
                        value="<?php echo htmlspecialchars($email); ?>"
                    >
                </div>

                <div class="form-group">
                    <label>รหัสผ่าน</label>
                    <input type="password" name="password" class="form-control" required>
                    <small class="text-muted">รหัสผ่านต้องเป็นตัวเลขอย่างน้อย 8 หลัก</small>
                </div>

                <div class="form-group">
                    <label>เบอร์โทรศัพท์</label>
                    <input
                        type="text"
                        name="phone_number"
                        class="form-control"
                        required
                        value="<?php echo htmlspecialchars($phone_number); ?>"
                    >
                </div>

                <button type="submit" class="btn custom-btn btn-block mt-3">สมัครสมาชิก</button>

                <p class="mt-3 text-center">
                    มีบัญชีอยู่แล้ว? <a href="login.php">เข้าสู่ระบบ</a>
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

</body>
</html>