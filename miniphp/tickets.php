<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';
include 'components/header.php';

$search = trim($_GET['q'] ?? '');
$locationFilter = trim($_GET['location'] ?? '');
$priceFilter = trim($_GET['price'] ?? '');

$sql = "
    SELECT
        r.raceid,
        r.racename,
        c.circuitname,
        c.location,
        c.country,
        MIN(t.price) AS min_price,
        MAX(t.price) AS max_price,
        SUM(t.availableseats) AS total_available,
        COUNT(t.ticketid) AS ticket_count,
        SUBSTRING_INDEX(
            GROUP_CONCAT(t.ticketid ORDER BY t.price ASC, t.ticketid ASC),
            ',',
            1
        ) AS cheapest_ticketid
    FROM tickets t
    INNER JOIN races r ON t.raceid = r.raceid
    INNER JOIN circuits c ON r.circuitid = c.circuitid
    GROUP BY
        r.raceid,
        r.racename,
        c.circuitname,
        c.location,
        c.country
    ORDER BY r.raceid ASC
";

$result = mysqli_query($connection, $sql);

$raceCards = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $totalAvailable = (int) $row['total_available'];
        $status = $totalAvailable <= 1000 ? 'Limited' : 'Available';

        $raceCards[] = [
            'raceid' => (int) $row['raceid'],
            'name' => $row['racename'],
            'location' => trim($row['location'] . ', ' . $row['country']),
            'circuitname' => $row['circuitname'],
            'status' => $status,
            'min_price' => (float) $row['min_price'],
            'max_price' => (float) $row['max_price'],
            'total_available' => $totalAvailable,
            'ticket_count' => (int) $row['ticket_count'],
            'cheapest_ticketid' => (int) $row['cheapest_ticketid'],
        ];
    }
}

$filteredCards = [];

foreach ($raceCards as $card) {
    $matchSearch = true;
    $matchLocation = true;
    $matchPrice = true;

    if ($search !== '') {
        $haystack = strtolower($card['name'] . ' ' . $card['location'] . ' ' . $card['circuitname']);
        $matchSearch = strpos($haystack, strtolower($search)) !== false;
    }

    if ($locationFilter !== '') {
        $matchLocation = stripos($card['location'], $locationFilter) !== false;
    }

    if ($priceFilter !== '') {
        $price = $card['min_price'];

        switch ($priceFilter) {
            case 'under-5000':
                $matchPrice = $price < 5000;
                break;
            case '5000-10000':
                $matchPrice = $price >= 5000 && $price <= 10000;
                break;
            case 'above-10000':
                $matchPrice = $price > 10000;
                break;
            default:
                $matchPrice = true;
                break;
        }
    }

    if ($matchSearch && $matchLocation && $matchPrice) {
        $filteredCards[] = $card;
    }
}

$locationOptions = [];
foreach ($raceCards as $card) {
    $locationOptions[] = $card['location'];
}
$locationOptions = array_values(array_unique($locationOptions));
sort($locationOptions);
?>

<main class="tickets-page">
    <div class="container tickets-container">

        <section class="tickets-header-block">
            <h1 class="tickets-page-title">Browse Tickets</h1>
            <p class="tickets-page-subtitle">
                Book your tickets for upcoming Formula 1 races around the world
            </p>
        </section>

        <section class="tickets-filter-wrap">
            <form method="GET" class="tickets-filter-form">
                <div class="tickets-search-box">
                    <span class="tickets-search-icon">⌕</span>
                    <input type="text" name="q" class="tickets-search-input" placeholder="Search races..."
                        value="<?php echo htmlspecialchars($search); ?>">
                </div>

                <select name="location" class="tickets-select">
                    <option value="">All Locations</option>
                    <?php foreach ($locationOptions as $option): ?>
                        <option value="<?php echo htmlspecialchars($option); ?>" <?php echo $locationFilter === $option ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($option); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="price" class="tickets-select">
                    <option value="">All Prices</option>
                    <option value="under-5000" <?php echo $priceFilter === 'under-5000' ? 'selected' : ''; ?>>Under 5,000
                        THB</option>
                    <option value="5000-10000" <?php echo $priceFilter === '5000-10000' ? 'selected' : ''; ?>>5,000 -
                        10,000 THB</option>
                    <option value="above-10000" <?php echo $priceFilter === 'above-10000' ? 'selected' : ''; ?>>Above
                        10,000 THB</option>
                </select>

                <button type="submit" class="tickets-filter-btn">
                    Apply Filters
                </button>
            </form>
        </section>

        <section class="tickets-grid-section">
            <div class="row g-4">
                <?php if (!empty($filteredCards)): ?>
                    <?php foreach ($filteredCards as $card): ?>
                        <?php
                        $statusClass = strtolower($card['status']) === 'limited' ? 'status-limited' : 'status-available';
                        ?>
                        <div class="col-xl-4 col-md-6">
                            <article class="ticket-race-card">
                                <div class="ticket-race-card-top">
                                    <h3 class="ticket-race-name"><?php echo htmlspecialchars($card['name']); ?></h3>
                                </div>

                                <div class="ticket-race-card-body">
                                    <div class="ticket-info-row">
                                        <span class="ticket-info-icon">📍</span>
                                        <span><?php echo htmlspecialchars($card['location']); ?></span>
                                    </div>

                                    <div class="ticket-info-row">
                                        <span class="ticket-info-icon">🏁</span>
                                        <span><?php echo htmlspecialchars($card['circuitname']); ?></span>
                                    </div>

                                    <div class="ticket-divider"></div>

                                    <div class="ticket-bottom-row">
                                        <div class="ticket-price-wrap">
                                            <span class="ticket-currency">฿</span>
                                            <span class="ticket-price"><?php echo number_format($card['min_price']); ?></span>
                                        </div>

                                        <span class="ticket-status-badge <?php echo $statusClass; ?>">
                                            <?php echo htmlspecialchars($card['status']); ?>
                                        </span>
                                    </div>

                                    <a href="race_tickets.php?raceid=<?php echo $card['raceid']; ?>" class="ticket-book-btn">
                                        View Tickets
                                    </a>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="tickets-empty-state">
                            No races found matching your filters.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>

    </div>
</main>

<?php include 'components/footer.php'; ?>