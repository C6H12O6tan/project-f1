<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$limit = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
$new_order = ($order === 'ASC') ? 'DESC' : 'ASC';

$query = "SELECT * FROM seating 
          ORDER BY seatid $order 
          LIMIT $start, $limit";
$result = mysqli_query($connection, $query);

$total_query = "SELECT COUNT(*) AS total FROM seating";
$total_result = mysqli_query($connection, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_seats = $total_row['total'];
$total_pages = ceil($total_seats / $limit);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการข้อมูลที่นั่งในสนาม | F1 Ticket Management</title>
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
                    <li class="nav-item"><a class="nav-link" href="admin_bookings.php">Manage Bookings</a></li>
                    <li class="nav-item active"><a class="nav-link" href="admin_seating.php">Seating</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="custom-header text-center py-4">
        <h1>Seating Management</h1>
        <p>จัดการข้อมูลที่นั่งในสนาม</p>
    </header>

    <div class="container mt-5">
        <div class="mb-3 text-right">
            <a href="add_seat.php" class="btn btn-success">+ เพิ่มที่นั่ง</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Seat ID</th>
                        <th>Zone</th>
                        <th>Row</th>
                        <th>Seat Number</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($seat = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($seat['seatid']); ?></td>
                            <td><?php echo htmlspecialchars($seat['section']); ?></td>
                            <td><?php echo htmlspecialchars($seat['rownumber']); ?></td>
                            <td><?php echo htmlspecialchars($seat['seatnumber']); ?></td>
                            <td><?php echo htmlspecialchars($seat['status']); ?></td>
                            <td>
                                <a href="edit_seat.php?seatid=<?php echo $seat['seatid']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="delete_seat.php?seatid=<?php echo $seat['seatid']; ?>" class="btn btn-sm btn-danger"
                                   onclick="return confirm('คุณต้องการลบที่นั่งนี้หรือไม่?');">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                    <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php } ?>
                <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>

    <footer class="custom-footer text-center py-3 mt-5">
        <p>&copy; 2025 F1 Ticket Management | All Rights Reserved By ME</p>
    </footer>

</body>
</html>
