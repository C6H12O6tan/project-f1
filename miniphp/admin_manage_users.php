<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db.php';

$query = "SELECT * FROM users";
$result = mysqli_query($connection, $query);

$current_page = 'admin_manage_users.php';
include 'components/admin_header.php';
?>

<header class="custom-header text-center py-4">
    <h1>User Management</h1>
    <p>จัดการข้อมูลผู้ใช้ทั้งหมดในระบบ</p>
</header>

<div class="container mt-5">
    <div class="mb-3 text-right">
        <a href="add_user.php" class="btn btn-success">+ เพิ่มผู้ใช้</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>User ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>User Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone_number']); ?></td>
                        <td><?php echo htmlspecialchars($user['user_type']); ?></td>
                        <td>
                            <a href="edit_user.php?user_id=<?php echo urlencode($user['user_id']); ?>"
                                class="btn btn-sm btn-primary">
                                Edit
                            </a>
                            <a href="delete_user.php?user_id=<?php echo urlencode($user['user_id']); ?>"
                                class="btn btn-sm btn-danger" onclick="return confirm('คุณต้องการลบผู้ใช้นี้หรือไม่?');">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'components/admin_footer.php'; ?>