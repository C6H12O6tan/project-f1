<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$limit = 20;  
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$total_query = "SELECT COUNT(*) AS total FROM bookings";
$total_result = mysqli_query($connection, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_pages = ceil($total_row['total'] / $limit);

$query = "SELECT b.bookingid, b.payment_date, b.paymentstatus, 
                 u.first_name, u.last_name, 
                 t.category, t.section
          FROM bookings b
          JOIN users u ON b.userid = u.user_id
          JOIN tickets t ON b.ticketid = t.ticketid
          ORDER BY b.bookingid ASC
          LIMIT $limit OFFSET $offset";
$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการการจอง| F1 Ticket Management</title>
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
                    <li class="nav-item"><a class="nav-link" href="admin_manage_tickets.php">Manage Tickets</a></li>
                    <li class="nav-item active"><a class="nav-link" href="admin_bookings.php">Manage Bookings</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_seating.php">Seating</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="custom-header text-center py-4">
        <h1>Bookings Management</h1>
        <p>จัดการข้อมูลการจองทั้งหมด</p>
    </header>

    <div class="container mt-5">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Booking ID</th>
                        <th>Booker</th>
                        <th>Ticket</th>
                        <th>Booking Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['bookingid']); ?></td>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['category']) . ' / ' . htmlspecialchars($row['section']); ?></td>
                            
                            <td><?php echo !empty($row['payment_date']) ? htmlspecialchars($row['payment_date']) : '<span class="text-danger">N/A</span>'; ?></td>

                            <td><?php echo !empty($row['paymentstatus']) ? htmlspecialchars($row['paymentstatus']) : '<span class="text-danger">N/A</span>'; ?></td>

                            <td>
                                <a href="edit_booking.php?bookingid=<?php echo $row['bookingid']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="delete_booking.php?bookingid=<?php echo $row['bookingid']; ?>" class="btn btn-sm btn-danger"
                                   onclick="return confirm('คุณต้องการลบรายการจองนี้หรือไม่?');">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <nav>
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="admin_bookings.php?page=<?php echo ($page - 1); ?>">« Prev</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="admin_bookings.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="admin_bookings.php?page=<?php echo ($page + 1); ?>">Next »</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <footer class="custom-footer text-center py-3 mt-5">
        <p>&copy; 2025 F1 Ticket Management | All Rights Reserved By ME</p>
    </footer>

</body>
</html>
