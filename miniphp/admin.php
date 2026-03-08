<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

/*
   Summary cards
*/
$totalUsers = 0;
$totalTickets = 0;
$totalBookings = 0;
$totalNews = 0;

$userResult = mysqli_query($connection, "SELECT COUNT(*) AS total FROM users");
if ($userResult) {
    $row = mysqli_fetch_assoc($userResult);
    $totalUsers = (int) $row['total'];
}

$ticketResult = mysqli_query($connection, "SELECT COUNT(*) AS total FROM tickets");
if ($ticketResult) {
    $row = mysqli_fetch_assoc($ticketResult);
    $totalTickets = (int) $row['total'];
}

$bookingResult = mysqli_query($connection, "SELECT COUNT(*) AS total FROM bookings");
if ($bookingResult) {
    $row = mysqli_fetch_assoc($bookingResult);
    $totalBookings = (int) $row['total'];
}

$newsResult = mysqli_query($connection, "SELECT COUNT(*) AS total FROM news");
if ($newsResult) {
    $row = mysqli_fetch_assoc($newsResult);
    $totalNews = (int) $row['total'];
}

/*
   Recent bookings
*/
$recentBookings = [];
$recentQuery = "
    SELECT 
        b.bookingid,
        b.payment_date,
        b.paymentstatus,
        u.first_name,
        u.last_name,
        t.category,
        t.section
    FROM bookings b
    JOIN users u ON b.userid = u.user_id
    JOIN tickets t ON b.ticketid = t.ticketid
    ORDER BY b.bookingid DESC
    LIMIT 6
";

$recentResult = mysqli_query($connection, $recentQuery);
if ($recentResult) {
    while ($row = mysqli_fetch_assoc($recentResult)) {
        $recentBookings[] = $row;
    }
}

$current_page = 'admin.php';
include 'components/admin_header.php';
?>

<div class="dashboard-page">
    <div class="container dashboard-container">
        <div class="dashboard-top">
            <h1 class="dashboard-title">Dashboard</h1>
            <p class="dashboard-subtitle">Welcome to F1 Ticket Management Admin Panel</p>
        </div>

        <div class="dashboard-stats">
            <div class="dashboard-stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon stat-icon-blue">👥</div>
                </div>
                <div class="stat-label">Total Users</div>
                <div class="stat-value"><?php echo number_format($totalUsers); ?></div>
            </div>

            <div class="dashboard-stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon stat-icon-red">🎟️</div>
                </div>
                <div class="stat-label">Total Tickets</div>
                <div class="stat-value"><?php echo number_format($totalTickets); ?></div>
            </div>

            <div class="dashboard-stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon stat-icon-green">📅</div>
                </div>
                <div class="stat-label">Total Reservations</div>
                <div class="stat-value"><?php echo number_format($totalBookings); ?></div>
            </div>

            <div class="dashboard-stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon stat-icon-purple">📰</div>
                </div>
                <div class="stat-label">Total News</div>
                <div class="stat-value"><?php echo number_format($totalNews); ?></div>
            </div>
        </div>

        <div class="dashboard-grid dashboard-grid-full">
            <div class="dashboard-panel">
                <h2 class="panel-title">Recent Bookings</h2>

                <div class="recent-bookings-list">
                    <?php if (!empty($recentBookings)): ?>
                        <?php foreach ($recentBookings as $booking): ?>
                            <?php
                            $fullName = trim(($booking['first_name'] ?? '') . ' ' . ($booking['last_name'] ?? ''));
                            $eventName = ($booking['category'] ?? '-') . ' / ' . ($booking['section'] ?? '-');
                            $bookingDate = !empty($booking['payment_date']) ? date('d M Y H:i', strtotime($booking['payment_date'])) : 'N/A';
                            $status = !empty($booking['paymentstatus']) ? $booking['paymentstatus'] : 'Pending';

                            $statusLower = strtolower($status);
                            $statusClass = 'status-pending';

                            if ($statusLower === 'confirmed' || $statusLower === 'paid' || $statusLower === 'success') {
                                $statusClass = 'status-confirmed';
                            } elseif ($statusLower === 'cancelled') {
                                $statusClass = 'status-cancelled';
                            }
                            ?>
                            <div class="recent-booking-card">
                                <div class="recent-booking-info">
                                    <div class="recent-booking-name"><?php echo htmlspecialchars($fullName !== '' ? $fullName : 'Unknown User'); ?></div>
                                    <div class="recent-booking-event"><?php echo htmlspecialchars($eventName); ?></div>
                                    <div class="recent-booking-date"><?php echo htmlspecialchars($bookingDate); ?></div>
                                </div>

                                <div class="recent-booking-status">
                                    <span class="booking-status-badge <?php echo $statusClass; ?>">
                                        <?php echo htmlspecialchars($status); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="recent-booking-empty">
                            ยังไม่มีข้อมูลการจองล่าสุด
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'components/admin_footer.php'; ?>