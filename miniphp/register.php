<?php
session_start();
include 'db.php';

$error = '';
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
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } elseif (!is_numeric($password) || strlen($password) < 8) {
        $error = 'Password must be numeric and at least 8 digits.';
    } else {
        $check_stmt = mysqli_prepare($connection, "SELECT user_id FROM users WHERE email = ? LIMIT 1");

        if ($check_stmt) {
            mysqli_stmt_bind_param($check_stmt, "s", $email);
            mysqli_stmt_execute($check_stmt);
            $check_result = mysqli_stmt_get_result($check_stmt);
            $existing_user = mysqli_fetch_assoc($check_result);
            mysqli_stmt_close($check_stmt);

            if ($existing_user) {
                $error = 'This email is already in use.';
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
                        $error = 'Registration failed.';
                    }

                    mysqli_stmt_close($insert_stmt);
                } else {
                    $error = 'System error. Please try again.';
                }
            }
        } else {
            $error = 'System error. Please try again.';
        }
    }
}

include 'components/auth_header.php';
?>

<main class="auth-main">
    <div class="auth-card">
        <h2 class="auth-title">Sign Up</h2>

        <form method="post">
            <div class="mb-3">
                <label for="first_name">First Name</label>
                <input
                    type="text"
                    id="first_name"
                    name="first_name"
                    class="form-control"
                    required
                    value="<?php echo htmlspecialchars($first_name); ?>"
                >
            </div>

            <div class="mb-3">
                <label for="last_name">Last Name</label>
                <input
                    type="text"
                    id="last_name"
                    name="last_name"
                    class="form-control"
                    required
                    value="<?php echo htmlspecialchars($last_name); ?>"
                >
            </div>

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

            <div class="mb-3">
                <label for="phone_number">Phone Number</label>
                <input
                    type="text"
                    id="phone_number"
                    name="phone_number"
                    class="form-control"
                    required
                    value="<?php echo htmlspecialchars($phone_number); ?>"
                >
            </div>

            <button type="submit" class="btn auth-btn mt-2">Sign Up</button>

            <p class="auth-link-text">
                Already have an account? <a href="login.php">Login</a>
            </p>

            <?php if ($error !== ''): ?>
                <p class="auth-error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
        </form>
    </div>
</main>

<?php include 'components/auth_footer.php'; ?>