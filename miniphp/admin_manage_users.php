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
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการผู้ใช้ | F1 Ticket Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark custom-navbar">
        <div class="container">
            <a class="navbar-brand" href="admin.php">Admin Dashboard</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#adminNavbar"
                aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="admin_manage_users.php">Manage Users</a></li>
                    <li class="nav-item active"><a class="nav-link" href="admin_manage_tickets.php">Manage Tickets</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_bookings.php">Manage Bookings</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_seating.php">Seating</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

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
                        <th>รหัสผู้ใช้</th>
                        <th>ชื่อ</th>
                        <th>นามสกุล</th>
                        <th>อีเมล</th>
                        <th>เบอร์โทร</th>
                        <th>ประเภทผู้ใช้</th>
                        <th>การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($user = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['phone_number']); ?></td>
                            <td><?php echo htmlspecialchars($user['user_type']); ?></td>
                            <td>
                                <a href="edit_user.php?user_id=<?php echo $user['user_id']; ?>" class="btn btn-sm btn-primary">Edti</a>
                                <a href="delete_user.php?user_id=<?php echo $user['user_id']; ?>" class="btn btn-sm btn-danger"
                                   onclick="return confirm('คุณต้องการลบผู้ใช้นี้หรือไม่?');">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="custom-footer text-center py-3 mt-5">
        <p>&copy; 2025 F1 Ticket Management | All Rights Reserved By ME</p>
    </footer>

</body>
</html>
