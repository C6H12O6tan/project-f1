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
   Summary cards */
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
   Monthly bookings (last 6 months) */
$monthlyMap = [];
$monthLabels = [];

for ($i = 5; $i >= 0; $i--) {
    $key = date('Y-m', strtotime("-$i month"));
    $label = date('M', strtotime("-$i month"));
    $monthlyMap[$key] = 0;
    $monthLabels[$key] = $label;
}

$monthlyQuery = "
    SELECT DATE_FORMAT(payment_date, '%Y-%m') AS month_key, COUNT(*) AS total
    FROM bookings
    WHERE payment_date IS NOT NULL
      AND payment_date >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 5 MONTH), '%Y-%m-01')
    GROUP BY DATE_FORMAT(payment_date, '%Y-%m')
    ORDER BY month_key ASC
";

$monthlyResult = mysqli_query($connection, $monthlyQuery);
if ($monthlyResult) {
    while ($row = mysqli_fetch_assoc($monthlyResult)) {
        if (isset($monthlyMap[$row['month_key']])) {
            $monthlyMap[$row['month_key']] = (int) $row['total'];
        }
    }
}

$maxMonthly = max($monthlyMap);
if ($maxMonthly <= 0) {
    $maxMonthly = 1;
}

/* Recent bookings */
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
    LIMIT 4
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
                    <div class="stat-change stat-change-green">+12%</div>
                </div>
                <div class="stat-label">Total Users</div>
                <div class="stat-value"><?php echo number_format($totalUsers); ?></div>
            </div>

            <div class="dashboard-stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon stat-icon-red">🎟️</div>
                    <div class="stat-change stat-change-green">+5%</div>
                </div>
                <div class="stat-label">Total Tickets</div>
                <div class="stat-value"><?php echo number_format($totalTickets); ?></div>
            </div>

            <div class="dashboard-stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon stat-icon-green">📅</div>
                    <div class="stat-change stat-change-green">+18%</div>
                </div>
                <div class="stat-label">Total Reservations</div>
                <div class="stat-value"><?php echo number_format($totalBookings); ?></div>
            </div>

            <div class="dashboard-stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon stat-icon-purple">📰</div>
                    <div class="stat-change stat-change-green">+8%</div>
                </div>
                <div class="stat-label">Total News</div>
                <div class="stat-value"><?php echo number_format($totalNews); ?></div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-panel">
                <h2 class="panel-title">Monthly Bookings</h2>

                <div class="bar-chart-box">
                    <div class="chart-y-axis">
                        <span><?php echo $maxMonthly; ?></span>
                        <span><?php echo (int) round($maxMonthly * 0.75); ?></span>
                        <span><?php echo (int) round($maxMonthly * 0.5); ?></span>
                        <span><?php echo (int) round($maxMonthly * 0.25); ?></span>
                        <span>0</span>
                    </div>

                    <div class="chart-main">
                        <div class="chart-grid-lines">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>

                        <div class="chart-bars">
                            <?php foreach ($monthlyMap as $monthKey => $count): ?>
                                <?php
                                $heightPercent = ($count / $maxMonthly) * 100;
                                if ($count > 0 && $heightPercent < 8) {
                                    $heightPercent = 8;
                                }
                                ?>
                                <div class="chart-bar-item">
                                    <div class="chart-bar-wrap">
                                        <div class="chart-bar" style="height: <?php echo $heightPercent; ?>%;"></div>
                                    </div>
                                    <div class="chart-bar-label"><?php echo $monthLabels[$monthKey]; ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-panel">
                <h2 class="panel-title">Recent Bookings</h2>

                <div class="recent-bookings-list">
                    <?php if (!empty($recentBookings)): ?>
                        <?php foreach ($recentBookings as $booking): ?>
                            <?php
                            $fullName = trim($booking['first_name'] . ' ' . $booking['last_name']);
                            $eventName = $booking['category'] . ' / ' . $booking['section'];
                            $bookingDate = !empty($booking['payment_date']) ? $booking['payment_date'] : 'N/A';
                            $status = !empty($booking['paymentstatus']) ? $booking['paymentstatus'] : 'Pending';

                            $statusClass = 'status-pending';
                            if (strtolower($status) === 'confirmed' || strtolower($status) === 'paid' || strtolower($status) === 'success') {
                                $statusClass = 'status-confirmed';
                            } elseif (strtolower($status) === 'cancelled') {
                                $statusClass = 'status-cancelled';
                            }
                            ?>
                            <div class="recent-booking-card">
                                <div class="recent-booking-info">
                                    <div class="recent-booking-name"><?php echo htmlspecialchars($fullName); ?></div>
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