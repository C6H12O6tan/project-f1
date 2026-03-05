<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$user_query = "SELECT COUNT(*) AS total_users FROM users";
$user_result = mysqli_query($connection, $user_query);
$user_data = $user_result ? mysqli_fetch_assoc($user_result) : ['total_users' => 0];
$total_users = $user_data['total_users'];

$ticket_query = "SELECT COUNT(*) AS total_tickets FROM tickets";
$ticket_result = mysqli_query($connection, $ticket_query);
$ticket_data = $ticket_result ? mysqli_fetch_assoc($ticket_result) : ['total_tickets' => 0];
$total_tickets = $ticket_data['total_tickets'];
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แอดมิน | F1 Ticket Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/admin.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.bundle.min.js"></script>
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

    <div class="container mt-5">
        <h2 class="text-center text-custom-red">Statistics</h2>
        <div class="row">
            <div class="col-md-6">
                <div class="card custom-card-color mb-3">
                    <div class="card-header">จำนวนผู้ใช้ทั้งหมด</div>
                    <div class="card-body">
                        <h3><?php echo htmlspecialchars($total_users); ?> คน</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card custom-card-color mb-3">
                    <div class="card-header">จำนวนประเภทของตั๋วที่มีอยู่</div>
                    <div class="card-body">
                        <h3><?php echo htmlspecialchars($total_tickets); ?> ประเภท</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="custom-footer text-center py-3 mt-5">
        <p>&copy; 2025 F1 Ticket Management | All Rights Reserved By ME</p>
    </footer>

</body>

</html>