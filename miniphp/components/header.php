<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../db.php';

$currentPage = basename($_SERVER['PHP_SELF']);
$userName = $_SESSION['username'] ?? $_SESSION['user_name'] ?? 'User';
$isLoggedIn = isset($_SESSION['user_id']);

$unreadCount = 0;

if ($isLoggedIn && isset($connection)) {
    $userId = (int) $_SESSION['user_id'];

    $countSql = "
        SELECT COUNT(*) AS total_unread
        FROM notifications
        WHERE user_id = ? AND is_read = 0
    ";

    $countStmt = mysqli_prepare($connection, $countSql);

    if ($countStmt) {
        mysqli_stmt_bind_param($countStmt, "i", $userId);
        mysqli_stmt_execute($countStmt);
        $countResult = mysqli_stmt_get_result($countStmt);
        $countRow = mysqli_fetch_assoc($countResult);
        $unreadCount = (int) ($countRow['total_unread'] ?? 0);
        mysqli_stmt_close($countStmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F1 Ticket Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/layout.css">

    <?php if ($currentPage === 'index.php'): ?>
        <link rel="stylesheet" href="css/index.css">
    <?php elseif ($currentPage === 'tickets.php'): ?>
        <link rel="stylesheet" href="css/ticket.css">
    <?php elseif ($currentPage === 'race_tickets.php'): ?>
        <link rel="stylesheet" href="css/race_tickets.css">
    <?php elseif ($currentPage === 'book.php'): ?>
        <link rel="stylesheet" href="css/book.css">
    <?php elseif ($currentPage === 'bookings.php'): ?>
        <link rel="stylesheet" href="css/bookings.css">
    <?php elseif ($currentPage === 'payment.php'): ?>
        <link rel="stylesheet" href="css/pay.css">
    <?php elseif ($currentPage === 'news.php' || $currentPage === 'news_detail.php'): ?>
        <link rel="stylesheet" href="css/news.css">
    <?php elseif ($currentPage === 'notifications.php'): ?>
        <link rel="stylesheet" href="css/notifications.css">
    <?php elseif ($currentPage === 'highlights.php'): ?>
        <link rel="stylesheet" href="css/highlights.css">
    <?php elseif ($currentPage === 'live_timing.php'): ?>
        <link rel="stylesheet" href="css/live_timing.css">
    <?php elseif ($currentPage === 'profile.php'): ?>
        <link rel="stylesheet" href="css/profile.css">
    <?php else: ?>
        <link rel="stylesheet" href="css/index.css">
    <?php endif; ?>
</head>

<body>

    <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container">
            <a class="navbar-brand custom-brand" href="index.php">F1 Ticket Management</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'index.php' ? 'active' : ''; ?>"
                            href="index.php">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage === 'tickets.php' || $currentPage === 'race_tickets.php' || $currentPage === 'book.php') ? 'active' : ''; ?>"
                            href="tickets.php">Tickets</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'bookings.php' ? 'active' : ''; ?>"
                            href="bookings.php">Reservation</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage === 'news.php' || $currentPage === 'news_detail.php') ? 'active' : ''; ?>"
                            href="news.php">News</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'highlights.php' ? 'active' : ''; ?>"
                            href="highlights.php">Highlights</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'live_timing.php' ? 'active' : ''; ?>"
                            href="live_timing.php">Live Timing</a>
                    </li>

                    <?php if ($isLoggedIn): ?>
                        <li class="nav-item">
                            <a class="nav-link position-relative <?php echo $currentPage === 'notifications.php' ? 'active' : ''; ?>"
                                href="notifications.php">
                                Notifications
                                <?php if ($unreadCount > 0): ?>
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        <?php echo $unreadCount; ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle user-menu-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="user-avatar-circle">👤</span>
                                <span><?php echo htmlspecialchars($userName); ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                <li><a class="dropdown-item" href="bookings.php">My Reservations</a></li>
                                <li>
                                    <a class="dropdown-item" href="notifications.php">
                                        Notifications<?php echo $unreadCount > 0 ? ' (' . $unreadCount . ')' : ''; ?>
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Sign up</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>