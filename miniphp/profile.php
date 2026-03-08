<?php
include 'components/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = (int) $_SESSION['user_id'];

$sql = "
    SELECT user_id, first_name, last_name, email, user_type
    FROM users
    WHERE user_id = ?
    LIMIT 1
";

$stmt = mysqli_prepare($connection, $sql);

if (!$stmt) {
    echo "<div class='container mt-5 mb-5'><div class='alert alert-danger'>Failed to load profile.</div></div>";
    include 'components/footer.php';
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$user) {
    echo "<div class='container mt-5 mb-5'><div class='alert alert-danger'>ไม่พบข้อมูลผู้ใช้</div></div>";
    include 'components/footer.php';
    exit();
}

$fullName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
$email = $user['email'] ?? '-';
$userType = !empty($user['user_type']) ? ucfirst($user['user_type']) : 'ไม่ระบุ';
?>

<div class="profile-page">
    <div class="container profile-container">
        <div class="profile-header">
            <h1 class="profile-title">My Profile</h1>
            <p class="profile-subtitle">Manage your account information for F1 Ticket Management.</p>
        </div>

        <div class="profile-card">
            <div class="profile-avatar-wrap">
                <div class="profile-avatar">
                    <?php echo strtoupper(substr($user['first_name'] ?? 'U', 0, 1)); ?>
                </div>
            </div>

            <div class="profile-info-list">
                <div class="profile-info-item">
                    <div class="profile-info-label">Full Name</div>
                    <div class="profile-info-value"><?php echo htmlspecialchars($fullName !== '' ? $fullName : '-'); ?>
                    </div>
                </div>

                <div class="profile-info-item">
                    <div class="profile-info-label">Email</div>
                    <div class="profile-info-value"><?php echo htmlspecialchars($email); ?></div>
                </div>

                <div class="profile-info-item">
                    <div class="profile-info-label">Role</div>
                    <div class="profile-info-value">
                        <span class="profile-role-badge">
                            <?php echo htmlspecialchars($userType); ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="profile-actions">
                <a href="logout.php" class="profile-btn profile-btn-danger">
                    Logout
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'components/footer.php'; ?>