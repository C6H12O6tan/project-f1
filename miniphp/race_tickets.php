<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';
include 'components/header.php';

$raceid = isset($_GET['raceid']) ? (int)$_GET['raceid'] : 0;

if ($raceid <= 0) {
    echo '<main class="race-ticket-page"><div class="container race-ticket-container"><div class="race-ticket-empty">Invalid race selected.</div></div></main>';
    include 'components/footer.php';
    exit();
}

$stmtRace = mysqli_prepare($connection, "
    SELECT
        r.raceid,
        r.racename,
        c.circuitname,
        c.location,
        c.country
    FROM races r
    INNER JOIN circuits c ON r.circuitid = c.circuitid
    WHERE r.raceid = ?
    LIMIT 1
");
mysqli_stmt_bind_param($stmtRace, "i", $raceid);
mysqli_stmt_execute($stmtRace);
$resultRace = mysqli_stmt_get_result($stmtRace);
$race = mysqli_fetch_assoc($resultRace);

if (!$race) {
    echo '<main class="race-ticket-page"><div class="container race-ticket-container"><div class="race-ticket-empty">Race not found.</div></div></main>';
    include 'components/footer.php';
    exit();
}

$stmtTickets = mysqli_prepare($connection, "
    SELECT
        ticketid,
        category,
        section,
        seatmode,
        price,
        totalseats,
        availableseats
    FROM tickets
    WHERE raceid = ?
    ORDER BY price ASC, ticketid ASC
");
mysqli_stmt_bind_param($stmtTickets, "i", $raceid);
mysqli_stmt_execute($stmtTickets);
$resultTickets = mysqli_stmt_get_result($stmtTickets);

$tickets = [];

function getSeatModeLabel(string $seatmode): string {
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

function getSeatModeDescription(string $seatmode): string {
    switch ($seatmode) {
        case 'general':
            return 'Access to general viewing areas with no fixed seat number.';
        case 'zoned':
            return 'Access to a specific grandstand or seating zone for better race viewing.';
        case 'premium':
            return 'Exclusive premium experience with hospitality benefits and premium access.';
        default:
            return 'Standard race ticket access.';
    }
}

while ($row = mysqli_fetch_assoc($resultTickets)) {
    $availableSeats = (int)$row['availableseats'];

    if ($availableSeats <= 0) {
        $status = 'Sold Out';
        $statusClass = 'soldout';
    } elseif ($availableSeats <= 1000) {
        $status = 'Limited';
        $statusClass = 'limited';
    } else {
        $status = 'Available';
        $statusClass = 'available';
    }

    $seatmode = $row['seatmode'] ?? 'general';

    $tickets[] = [
        'ticketid' => (int)$row['ticketid'],
        'category' => $row['category'],
        'section' => $row['section'],
        'seatmode' => $seatmode,
        'seatmodeLabel' => getSeatModeLabel($seatmode),
        'seatmodeDescription' => getSeatModeDescription($seatmode),
        'price' => (float)$row['price'],
        'totalseats' => (int)$row['totalseats'],
        'availableseats' => $availableSeats,
        'status' => $status,
        'statusClass' => $statusClass,
    ];
}
?>

<main class="race-ticket-page">
    <div class="container race-ticket-container">

        <section class="race-ticket-header">
            <a href="tickets.php" class="race-ticket-back-link">← Back to Tickets</a>

            <h1 class="race-ticket-title"><?php echo htmlspecialchars($race['racename']); ?></h1>

            <div class="race-ticket-meta">
                <span>📍 <?php echo htmlspecialchars($race['location'] . ', ' . $race['country']); ?></span>
                <span>🏁 <?php echo htmlspecialchars($race['circuitname']); ?></span>
            </div>
        </section>

        <section class="race-ticket-list-section">
            <?php if (!empty($tickets)): ?>
                <div class="row g-4">
                    <?php foreach ($tickets as $ticket): ?>
                        <div class="col-lg-4 col-md-6">
                            <article class="race-ticket-card">
                                <div class="race-ticket-card-top">
                                    <h3 class="race-ticket-category">
                                        <?php echo htmlspecialchars($ticket['category']); ?>
                                    </h3>
                                    <p class="race-ticket-section">
                                        <?php echo htmlspecialchars($ticket['section']); ?>
                                    </p>
                                </div>

                                <div class="race-ticket-card-body">
                                    <div class="race-ticket-mode-badge">
                                        <?php echo htmlspecialchars($ticket['seatmodeLabel']); ?>
                                    </div>

                                    <p class="race-ticket-description">
                                        <?php echo htmlspecialchars($ticket['seatmodeDescription']); ?>
                                    </p>

                                    <div class="race-ticket-row">
                                        <span class="race-ticket-label">Price</span>
                                        <span class="race-ticket-value price">
                                            ฿<?php echo number_format($ticket['price']); ?>
                                        </span>
                                    </div>

                                    <div class="race-ticket-row">
                                        <span class="race-ticket-label">Available Seats</span>
                                        <span class="race-ticket-value">
                                            <?php echo number_format($ticket['availableseats']); ?>
                                        </span>
                                    </div>

                                    <div class="race-ticket-row">
                                        <span class="race-ticket-label">Status</span>
                                        <span class="race-ticket-status <?php echo $ticket['statusClass']; ?>">
                                            <?php echo htmlspecialchars($ticket['status']); ?>
                                        </span>
                                    </div>

                                    <?php if ($ticket['availableseats'] > 0): ?>
                                        <a href="book.php?ticketid=<?php echo urlencode($ticket['ticketid']); ?>" class="race-ticket-book-btn">
                                            Book This Ticket
                                        </a>
                                    <?php else: ?>
                                        <span class="race-ticket-book-btn disabled">
                                            Sold Out
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="race-ticket-empty">
                    No ticket types available for this race yet.
                </div>
            <?php endif; ?>
        </section>

    </div>
</main>

<?php include 'components/footer.php'; ?>