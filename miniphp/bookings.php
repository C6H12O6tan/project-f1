<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';
include 'components/header.php';

$userId = (int) $_SESSION['user_id'];

$sql = "
    SELECT
        b.bookingid,
        b.quantity,
        b.totalprice,
        b.paymentstatus,
        b.bookingdate,
        t.category,
        t.section,
        t.seatmode,
        r.racename,
        c.circuitname,
        c.location,
        c.country
    FROM bookings b
    JOIN tickets t ON b.ticketid = t.ticketid
    JOIN races r ON t.raceid = r.raceid
    JOIN circuits c ON r.circuitid = c.circuitid
    WHERE b.userid = ?
    ORDER BY b.bookingdate DESC, b.bookingid DESC
";

$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<main class="bookings-page">
    <div class="container bookings-container">

        <section class="bookings-header">
            <h1 class="bookings-title">My Reservations</h1>
            <p class="bookings-subtitle">
                View your booked Formula 1 tickets and reservation details.
            </p>
        </section>

        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <div class="row g-4">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <?php
                    $statusText = trim((string) ($row['paymentstatus'] ?? 'Unknown'));
                    $statusNormalized = strtolower($statusText);

                    if ($statusNormalized === 'paid') {
                        $statusClass = 'paid';
                    } elseif ($statusNormalized === 'pending') {
                        $statusClass = 'pending';
                    } elseif ($statusNormalized === 'cancelled' || $statusNormalized === 'canceled') {
                        $statusClass = 'cancelled';
                    } else {
                        $statusClass = 'default';
                    }

                    $seatmode = strtolower((string) ($row['seatmode'] ?? ''));
                    if ($seatmode === 'general') {
                        $seatModeText = 'No assigned seat';
                    } elseif ($seatmode === 'zoned') {
                        $seatModeText = 'Reserved zone';
                    } elseif ($seatmode === 'premium') {
                        $seatModeText = 'Premium hospitality';
                    } else {
                        $seatModeText = 'Standard access';
                    }

                    $bookingDate = '-';
                    if (!empty($row['bookingdate']) && strtotime($row['bookingdate']) !== false) {
                        $bookingDate = date('F j, Y', strtotime($row['bookingdate']));
                    }

                    $isPending = ($statusNormalized === 'pending');
                    ?>
                    <div class="col-lg-6">
                        <article class="booking-card">
                            <div class="booking-header-row">
                                <h3 class="booking-race-name">
                                    <?php echo htmlspecialchars($row['racename']); ?>
                                </h3>

                                <span class="booking-status booking-status-<?php echo htmlspecialchars($statusClass); ?>">
                                    <?php echo htmlspecialchars($statusText); ?>
                                </span>
                            </div>

                            <div class="booking-body">
                                <p class="booking-text">
                                    <span class="booking-icon">📍</span>
                                    <?php echo htmlspecialchars($row['location'] . ', ' . $row['country']); ?>
                                </p>

                                <p class="booking-text">
                                    <span class="booking-icon">🏁</span>
                                    <?php echo htmlspecialchars($row['circuitname']); ?>
                                </p>

                                <p class="booking-text">
                                    <span class="booking-icon">🎫</span>
                                    <?php echo htmlspecialchars($row['category']); ?> - <?php echo htmlspecialchars($row['section']); ?>
                                </p>

                                <p class="booking-text">
                                    <span class="booking-label">Access:</span>
                                    <?php echo htmlspecialchars($seatModeText); ?>
                                </p>

                                <p class="booking-text">
                                    <span class="booking-label">Quantity:</span>
                                    <?php echo (int) $row['quantity']; ?>
                                </p>

                                <p class="booking-price">
                                    <span class="booking-label">Total:</span>
                                    ฿<?php echo number_format((float) $row['totalprice']); ?>
                                </p>

                                <p class="booking-date">
                                    Booked on: <?php echo htmlspecialchars($bookingDate); ?>
                                </p>
                            </div>

                            <?php if ($isPending): ?>
                                <div class="booking-actions">
                                    <a href="payment.php?bookingid=<?php echo urlencode($row['bookingid']); ?>" class="booking-btn booking-btn-pay">
                                        Pay Now
                                    </a>
                                    <a href="cancel_booking.php?bookingid=<?php echo urlencode($row['bookingid']); ?>" class="booking-btn booking-btn-cancel" onclick="return confirm('Are you sure you want to cancel this booking?');">
                                        Cancel
                                    </a>
                                </div>
                            <?php endif; ?>
                        </article>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="booking-empty">
                You have no reservations yet.
            </div>
        <?php endif; ?>

    </div>
</main>

<?php include 'components/footer.php'; ?>