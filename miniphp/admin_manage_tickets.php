<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM tickets ORDER BY ticketid ASC";
$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการข้อมูลตั๋ว | F1 Ticket Management</title>
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
        <h1>Tickets Management</h1>
        <p>จัดการข้อมูลตั๋ว F1 ทั้งหมด</p>
    </header>

    <div class="container mt-5">
        <div class="mb-3 text-right">
            <a href="add_ticket.php" class="btn btn-success">+ เพิ่มตั๋ว</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Ticket ID</th>
                        <th>Category</th>
                        <th>Section</th>
                        <th>Price</th>
                        <th>Available Seats</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($ticket = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ticket['ticketid']); ?></td>
                            <td><?php echo htmlspecialchars($ticket['category']); ?></td>
                            <td><?php echo htmlspecialchars($ticket['section']); ?></td>
                            <td><?php echo number_format($ticket['price']); ?></td>
                            <td><?php echo htmlspecialchars($ticket['availableseats']); ?></td>
                            <td>
                                <a href="edit_ticket.php?ticketid=<?php echo $ticket['ticketid']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="delete_ticket.php?ticketid=<?php echo $ticket['ticketid']; ?>" class="btn btn-sm btn-danger"
                                   onclick="return confirm('คุณต้องการลบตั๋วนี้หรือไม่?');">Delete</a>
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
