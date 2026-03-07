<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | F1 Ticket Management</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/admin.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="admin-page">

    <nav class="navbar navbar-expand-xl navbar-dark custom-navbar">
        <div class="container-fluid admin-nav-wrap">

            <a class="navbar-brand admin-brand" href="admin.php">Admin System</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#adminNavbar"
                aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="adminNavbar">

                <ul class="navbar-nav admin-menu mx-auto">

                    <li class="nav-item <?php echo ($current_page === 'admin_manage_users.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="admin_manage_users.php">Manage Users</a>
                    </li>

                    <li class="nav-item <?php echo ($current_page === 'admin_manage_tickets.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="admin_manage_tickets.php">Manage Tickets</a>
                    </li>

                    <li class="nav-item <?php echo ($current_page === 'admin_bookings.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="admin_bookings.php">Manage Bookings</a>
                    </li>

                    <li class="nav-item <?php echo ($current_page === 'admin_seating.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="admin_seating.php">Seating</a>
                    </li>

                    <li class="nav-item <?php echo ($current_page === 'admin_news.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="admin_news.php">Manage News</a>
                    </li>

                    <li class="nav-item <?php echo ($current_page === 'admin_highlights.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="admin_highlights.php">Manage Highlights</a>
                    </li>

                    <li class="nav-item <?php echo ($current_page === 'admin_live_timing.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="admin_live_timing.php">Manage Live Timing</a>
                    </li>
                </ul>

                <ul class="navbar-nav admin-logout ml-xl-3">
                    <li class="nav-item">
                        <a class="nav-link logout-link" href="logout.php">Logout</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>

    <main class="admin-main">