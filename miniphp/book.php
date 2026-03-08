<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';

$userId = (int) $_SESSION['user_id'];
$ticketId = isset($_GET['ticketid']) ? (int) $_GET['ticketid'] : 0;

function getSeatModeLabel(string $seatmode): string
{
    switch ($seatmode) {
        case 'general':
            return 'No assigned seat';
        case 'zoned':
            return 'Reserved zone seating';
        case 'premium':
            return 'Premium hospitality access';
        default:
            return 'Ticket access';
    }
}

function getSeatModeDescription(string $seatmode): string
{
    switch ($seatmode) {
        case 'general':
            return 'Access to general viewing areas with no fixed seat number.';
        case 'zoned':
            return 'Access to a specific grandstand or seating zone for better race viewing.';
        case 'premium':
            return 'Exclusive premium experience with hospitality benefits and premium access.';
        default:
            return 'Standard ticket access.';
    }
}

if ($ticketId <= 0) {
    include 'components/header.php';
    echo '<main class="book-page"><div class="container book-container"><div class="book-alert-error">Invalid ticket selected.</div></div></main>';
    include 'components/footer.php';
    exit();
}

$stmt = mysqli_prepare($connection, "
    SELECT
        t.ticketid,
        t.raceid,
        t.category,
        t.section,
        t.seatmode,
        t.price,
        t.totalseats,
        t.availableseats,
        r.racename,
        c.circuitname,
        c.location,
        c.country
    FROM tickets t
    INNER JOIN races r ON t.raceid = r.raceid
    INNER JOIN circuits c ON r.circuitid = c.circuitid
    WHERE t.ticketid = ?
    LIMIT 1
");

mysqli_stmt_bind_param($stmt, "i", $ticketId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$ticket = mysqli_fetch_assoc($result);

if (!$ticket) {
    include 'components/header.php';
    echo '<main class="book-page"><div class="container book-container"><div class="book-alert-error">Ticket not found.</div></div></main>';
    include 'components/footer.php';
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 0;

    if ($quantity <= 0) {
        $error = 'Please enter a valid quantity.';
    } elseif ($quantity > (int) $ticket['availableseats']) {
        $error = 'Not enough available seats for this ticket.';
    } else {
        mysqli_begin_transaction($connection);

        try {

            $stmtCheck = mysqli_prepare($connection, "
                SELECT availableseats
                FROM tickets
                WHERE ticketid = ?
                LIMIT 1
                FOR UPDATE
            ");
            mysqli_stmt_bind_param($stmtCheck, "i", $ticketId);
            mysqli_stmt_execute($stmtCheck);
            $resultCheck = mysqli_stmt_get_result($stmtCheck);
            $freshTicket = mysqli_fetch_assoc($resultCheck);

            if (!$freshTicket) {
                throw new Exception('Ticket not found during booking.');
            }

            $currentAvailable = (int) $freshTicket['availableseats'];

            if ($quantity > $currentAvailable) {
                throw new Exception('The selected quantity exceeds available seats.');
            }

            $totalPrice = $quantity * (float) $ticket['price'];

            $stmtInsert = mysqli_prepare($connection, "
                INSERT INTO bookings (userid, ticketid, quantity, totalprice, paymentstatus)
                VALUES (?, ?, ?, ?, 'Pending')
            ");
            mysqli_stmt_bind_param($stmtInsert, "iiid", $userId, $ticketId, $quantity, $totalPrice);

            if (!mysqli_stmt_execute($stmtInsert)) {
                throw new Exception('Failed to create booking.');
            }

            $stmtUpdate = mysqli_prepare($connection, "
                UPDATE tickets
                SET availableseats = availableseats - ?
                WHERE ticketid = ?
            ");
            mysqli_stmt_bind_param($stmtUpdate, "ii", $quantity, $ticketId);

            if (!mysqli_stmt_execute($stmtUpdate)) {
                throw new Exception('Failed to update available seats.');
            }

            mysqli_commit($connection);

            header('Location: bookings.php?success=1');
            exit();
        } catch (Throwable $e) {
            mysqli_rollback($connection);
            $error = $e->getMessage();
        }
    }
}

include 'components/header.php';

$availableSeats = (int) $ticket['availableseats'];

if ($availableSeats <= 0) {
    $statusText = 'Sold Out';
    $statusClass = 'soldout';
} elseif ($availableSeats <= 1000) {
    $statusText = 'Limited';
    $statusClass = 'limited';
} else {
    $statusText = 'Available';
    $statusClass = 'available';
}
?>

<main class="book-page">
    <div class="container book-container">

        <section class="book-header">
            <a href="race_tickets.php?raceid=<?php echo urlencode($ticket['raceid']); ?>" class="book-back-link">← Back to Ticket Types</a>
            <h1 class="book-title">Book Ticket</h1>
            <p class="book-subtitle">Review your selected ticket and confirm your booking.</p>
        </section>

        <?php if ($error !== ''): ?>
            <div class="book-alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-7">
                <section class="book-card">
                    <div class="book-card-head">
                        <h2 class="book-card-title"><?php echo htmlspecialchars($ticket['racename']); ?></h2>
                        <span class="book-status-badge <?php echo $statusClass; ?>">
                            <?php echo htmlspecialchars($statusText); ?>
                        </span>
                    </div>

                    <div class="book-info-grid">
                        <div class="book-info-item">
                            <span class="book-label">Location</span>
                            <span class="book-value"><?php echo htmlspecialchars($ticket['location'] . ', ' . $ticket['country']); ?></span>
                        </div>

                        <div class="book-info-item">
                            <span class="book-label">Circuit</span>
                            <span class="book-value"><?php echo htmlspecialchars($ticket['circuitname']); ?></span>
                        </div>

                        <div class="book-info-item">
                            <span class="book-label">Category</span>
                            <span class="book-value"><?php echo htmlspecialchars($ticket['category']); ?></span>
                        </div>

                        <div class="book-info-item">
                            <span class="book-label">Section</span>
                            <span class="book-value"><?php echo htmlspecialchars($ticket['section']); ?></span>
                        </div>

                        <div class="book-info-item">
                            <span class="book-label">Access Type</span>
                            <span class="book-value"><?php echo htmlspecialchars(getSeatModeLabel($ticket['seatmode'] ?? 'general')); ?></span>
                        </div>

                        <div class="book-info-item">
                            <span class="book-label">Available Seats</span>
                            <span class="book-value"><?php echo number_format($availableSeats); ?></span>
                        </div>

                        <div class="book-info-item book-info-item-full">
                            <span class="book-label">Description</span>
                            <span class="book-value book-description">
                                <?php echo htmlspecialchars(getSeatModeDescription($ticket['seatmode'] ?? 'general')); ?>
                            </span>
                        </div>

                        <div class="book-info-item book-info-item-full">
                            <span class="book-label">Price / Ticket</span>
                            <span class="book-value book-price">฿<?php echo number_format((float) $ticket['price']); ?></span>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-lg-5">
                <section class="book-form-card">
                    <h2 class="book-form-title">Confirm Booking</h2>

                    <?php if ($availableSeats > 0): ?>
                        <form method="POST" class="book-form">
                            <div class="book-field">
                                <label for="quantity" class="book-field-label">Quantity</label>
                                <input
                                    type="number"
                                    id="quantity"
                                    name="quantity"
                                    class="book-input"
                                    min="1"
                                    max="<?php echo $availableSeats; ?>"
                                    value="1"
                                    required
                                >
                            </div>

                            <div class="book-summary">
                                <div class="book-summary-row">
                                    <span>Ticket Type</span>
                                    <span><?php echo htmlspecialchars($ticket['category']); ?></span>
                                </div>
                                <div class="book-summary-row">
                                    <span>Section</span>
                                    <span><?php echo htmlspecialchars($ticket['section']); ?></span>
                                </div>
                                <div class="book-summary-row">
                                    <span>Access Type</span>
                                    <span><?php echo htmlspecialchars(getSeatModeLabel($ticket['seatmode'] ?? 'general')); ?></span>
                                </div>
                                <div class="book-summary-row">
                                    <span>Unit Price</span>
                                    <span>฿<?php echo number_format((float) $ticket['price']); ?></span>
                                </div>
                            </div>

                            <button type="submit" class="book-submit-btn">
                                Confirm Booking
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="book-soldout-box">
                            This ticket is currently sold out.
                        </div>
                    <?php endif; ?>
                </section>
            </div>
        </div>

    </div>
</main>

<?php include 'components/footer.php'; ?>