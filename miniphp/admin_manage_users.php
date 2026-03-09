<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
   รองรับทั้ง role และ user_type ใน session
*/
$sessionUserType = $_SESSION['user_type'] ?? ($_SESSION['role'] ?? '');

if (!isset($_SESSION['user_id']) || $sessionUserType !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db.php';

$currentAdminId = (int) $_SESSION['user_id'];

$query = "
    SELECT 
        u.user_id,
        u.first_name,
        u.last_name,
        u.email,
        u.phone_number,
        u.user_type,
        COUNT(b.bookingid) AS booking_count
    FROM users u
    LEFT JOIN bookings b ON u.user_id = b.userid
    GROUP BY 
        u.user_id,
        u.first_name,
        u.last_name,
        u.email,
        u.phone_number,
        u.user_type
    ORDER BY u.user_id ASC
";

$result = mysqli_query($connection, $query);

$current_page = 'admin_manage_users.php';
include 'components/admin_header.php';
?>

<header class="custom-header text-center py-4">
    <h1>User Management</h1>
    <p>จัดการข้อมูลผู้ใช้ทั้งหมดในระบบ</p>
</header>

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div></div>
        <a href="add_user.php" class="btn btn-success">+ เพิ่มผู้ใช้</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="thead-dark">
                <tr>
                    <th>User ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>User Type</th>
                    <th>Bookings</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($user = mysqli_fetch_assoc($result)): ?>
                        <?php
                        $userId = (int) ($user['user_id'] ?? 0);
                        $bookingCount = (int) ($user['booking_count'] ?? 0);
                        $isCurrentAdmin = ($userId === $currentAdminId);
                        $userType = $user['user_type'] ?? '-';
                        $canDelete = !$isCurrentAdmin && $bookingCount === 0;
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars((string) $userId); ?></td>
                            <td><?php echo htmlspecialchars($user['first_name'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($user['last_name'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($user['email'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($user['phone_number'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($userType); ?></td>
                            <td>
                                <?php if ($bookingCount > 0): ?>
                                    <span class="badge badge-warning">
                                        <?php echo $bookingCount; ?> booking(s)
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">0</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap" style="gap: 8px;">
                                    <a
                                        href="edit_user.php?user_id=<?php echo urlencode((string) $userId); ?>"
                                        class="btn btn-sm btn-primary"
                                    >
                                        Edit
                                    </a>

                                    <?php if ($isCurrentAdmin): ?>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-secondary"
                                            disabled
                                            title="ไม่สามารถลบบัญชีของตัวเองได้"
                                        >
                                            Delete
                                        </button>
                                    <?php elseif ($bookingCount > 0): ?>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-secondary"
                                            disabled
                                            title="ไม่สามารถลบผู้ใช้ที่มีประวัติการจองได้"
                                        >
                                            Delete
                                        </button>
                                    <?php elseif ($canDelete): ?>
                                        <a
                                            href="delete_user.php?user_id=<?php echo urlencode((string) $userId); ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('คุณต้องการลบผู้ใช้นี้หรือไม่?');"
                                        >
                                            Delete
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">ไม่พบข้อมูลผู้ใช้ในระบบ</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <small class="text-muted">
            หมายเหตุ: ผู้ใช้ที่มีประวัติการจอง หรือบัญชีของ admin ที่กำลังล็อกอินอยู่ จะไม่สามารถลบได้
        </small>
    </div>
</div>

<?php include 'components/admin_footer.php'; ?>