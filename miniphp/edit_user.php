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

if ($userId <= 0) {
    echo "<script>alert('ไม่พบรหัสผู้ใช้!'); window.location.href='admin_manage_users.php';</script>";
    exit();
}

/* ดึงข้อมูลผู้ใช้ */
$selectSql = "
    SELECT user_id, first_name, last_name, email, phone_number, user_type
    FROM users
    WHERE user_id = ?
    LIMIT 1
";

$selectStmt = mysqli_prepare($connection, $selectSql);

if (!$selectStmt) {
    echo "<script>alert('เกิดข้อผิดพลาดในการโหลดข้อมูลผู้ใช้'); window.location.href='admin_manage_users.php';</script>";
    exit();
}

mysqli_stmt_bind_param($selectStmt, "i", $userId);
mysqli_stmt_execute($selectStmt);
$result = mysqli_stmt_get_result($selectStmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($selectStmt);

if (!$user) {
    echo "<script>alert('ไม่พบข้อมูลผู้ใช้!'); window.location.href='admin_manage_users.php';</script>";
    exit();
}

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phoneNumber = trim($_POST['phone_number'] ?? '');
    $userType = trim($_POST['user_type'] ?? '');

    $allowedUserTypes = ['user', 'admin'];

    if ($firstName === '' || $lastName === '' || $email === '' || $phoneNumber === '' || $userType === '') {
        $errorMessage = 'กรุณากรอกข้อมูลให้ครบถ้วน';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = 'รูปแบบอีเมลไม่ถูกต้อง';
    } elseif (!in_array($userType, $allowedUserTypes, true)) {
        $errorMessage = 'ประเภทผู้ใช้ไม่ถูกต้อง';
    } else {
        /* เช็กอีเมลซ้ำ ยกเว้น user คนเดิม */
        $emailCheckSql = "SELECT user_id FROM users WHERE email = ? AND user_id <> ? LIMIT 1";
        $emailCheckStmt = mysqli_prepare($connection, $emailCheckSql);

        if (!$emailCheckStmt) {
            $errorMessage = 'เกิดข้อผิดพลาดในการตรวจสอบอีเมล';
        } else {
            mysqli_stmt_bind_param($emailCheckStmt, "si", $email, $userId);
            mysqli_stmt_execute($emailCheckStmt);
            $emailCheckResult = mysqli_stmt_get_result($emailCheckStmt);
            $emailExists = mysqli_fetch_assoc($emailCheckResult);
            mysqli_stmt_close($emailCheckStmt);

            if ($emailExists) {
                $errorMessage = 'อีเมลนี้ถูกใช้งานแล้ว';
            }
        }
    }

    if ($errorMessage === '') {
        $updateSql = "
            UPDATE users
            SET first_name = ?, last_name = ?, email = ?, phone_number = ?, user_type = ?
            WHERE user_id = ?
        ";

        $updateStmt = mysqli_prepare($connection, $updateSql);

        if (!$updateStmt) {
            $errorMessage = 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล';
        } else {
            mysqli_stmt_bind_param(
                $updateStmt,
                "sssssi",
                $firstName,
                $lastName,
                $email,
                $phoneNumber,
                $userType,
                $userId
            );

            if (mysqli_stmt_execute($updateStmt)) {
                mysqli_stmt_close($updateStmt);
                echo "<script>alert('อัปเดตข้อมูลผู้ใช้สำเร็จ'); window.location.href='admin_manage_users.php';</script>";
                exit();
            } else {
                $errorMessage = 'ไม่สามารถบันทึกการเปลี่ยนแปลงได้';
                mysqli_stmt_close($updateStmt);
            }
        }
    }

    /* ให้ค่าฟอร์มคงอยู่หลัง submit */
    $user['first_name'] = $firstName;
    $user['last_name'] = $lastName;
    $user['email'] = $email;
    $user['phone_number'] = $phoneNumber;
    $user['user_type'] = $userType;
}

$current_page = 'admin_manage_users.php';
include 'components/admin_header.php';
?>

<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h2 class="mb-4">แก้ไขผู้ใช้</h2>

                    <?php if ($errorMessage !== ''): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($errorMessage); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label fw-bold">ชื่อ</label>
                                <input
                                    type="text"
                                    id="first_name"
                                    name="first_name"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>"
                                    required
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label fw-bold">นามสกุล</label>
                                <input
                                    type="text"
                                    id="last_name"
                                    name="last_name"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>"
                                    required
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-bold">อีเมล</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                                    required
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone_number" class="form-label fw-bold">เบอร์โทร</label>
                                <input
                                    type="text"
                                    id="phone_number"
                                    name="phone_number"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>"
                                    required
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="user_type" class="form-label fw-bold">ประเภทผู้ใช้</label>
                                <select id="user_type" name="user_type" class="form-control" required>
                                    <option value="user" <?php echo (($user['user_type'] ?? '') === 'user') ? 'selected' : ''; ?>>
                                        user
                                    </option>
                                    <option value="admin" <?php echo (($user['user_type'] ?? '') === 'admin') ? 'selected' : ''; ?>>
                                        admin
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex gap-2 flex-wrap mt-2">
                            <button type="submit" class="btn btn-danger">
                                บันทึกการเปลี่ยนแปลง
                            </button>
                            <a href="admin_manage_users.php" class="btn btn-outline-secondary">
                                กลับ
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'components/admin_footer.php'; ?>