<?php
session_start();
include 'db.php';

$error = '';
$email = '';

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
                    $login_success = true;

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

include 'components/auth_header.php';
?>

<main class="auth-main">
    <div class="auth-card">
        <h2 class="auth-title">Login</h2>

        <form method="post">
            <div class="mb-3">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    required
                    value="<?php echo htmlspecialchars($email); ?>"
                >
            </div>

            <div class="mb-3">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    required
                >
            </div>

            <button type="submit" class="btn auth-btn mt-2">Login</button>

            <p class="auth-link-text">
                Don’t have an account? <a href="register.php">Sign up</a>
            </p>

            <?php if ($error !== ''): ?>
                <p class="auth-error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
        </form>
    </div>
</main>

<?php include 'components/auth_footer.php'; ?>