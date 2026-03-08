<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$ticketid = isset($_GET['ticketid']) ? (int) $_GET['ticketid'] : 0;

if ($ticketid <= 0) {
    include 'components/header.php';
    echo '<div class="container mt-5 mb-5"><div class="alert alert-danger">Invalid ticket selected.</div></div>';
    include 'components/footer.php';
    exit();
}

$query = "
    SELECT 
        t.ticketid,
        t.category,
        t.section,
        t.price,
        t.seatmode,
        t.availableseats,
        r.racename
    FROM tickets t
    JOIN races r ON t.raceid = r.raceid
    WHERE t.ticketid = ?
    LIMIT 1
";

$stmt = $connection->prepare($query);
$stmt->bind_param("i", $ticketid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    include 'components/header.php';
    echo '<div class="container mt-5 mb-5"><div class="alert alert-danger">Ticket not found.</div></div>';
    include 'components/footer.php';
    exit();
}

$ticket = $result->fetch_assoc();
$stmt->close();

$seatmode = strtolower(trim($ticket['seatmode'] ?? 'general'));
$isReserved = in_array($seatmode, ['zoned', 'premium'], true);

$seats = [];
$seatCount = 0;

if ($isReserved) {
    $seat_query = "
        SELECT seatid, ticketid, section, rownumber, seatnumber, status
        FROM seating
        WHERE ticketid = ?
        ORDER BY rownumber ASC, CAST(seatnumber AS UNSIGNED) ASC, seatnumber ASC
    ";

    $stmt = $connection->prepare($seat_query);
    $stmt->bind_param("i", $ticketid);
    $stmt->execute();
    $seat_result = $stmt->get_result();

    while ($row = $seat_result->fetch_assoc()) {
        $seats[$row['rownumber']][] = $row;
        $seatCount++;
    }

    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid = (int) $_SESSION['user_id'];

    if ($isReserved) {
        $selectedSeats = $_POST['seats'] ?? [];

        if (!is_array($selectedSeats) || empty($selectedSeats)) {
            echo "<script>alert('Please select at least one seat');</script>";
        } else {
            $selectedSeats = array_values(array_unique(array_map('intval', $selectedSeats)));
            $quantity = count($selectedSeats);
            $totalprice = (float) $ticket['price'] * $quantity;

            mysqli_begin_transaction($connection);

            try {
                $insertBooking = "
                    INSERT INTO bookings (userid, ticketid, quantity, totalprice, paymentstatus)
                    VALUES (?, ?, ?, ?, 'pending')
                ";

                $stmt = $connection->prepare($insertBooking);
                $stmt->bind_param("iiid", $userid, $ticketid, $quantity, $totalprice);
                $stmt->execute();
                $bookingid = $connection->insert_id;
                $stmt->close();

                foreach ($selectedSeats as $seatid) {
                    $checkSeat = "
                        SELECT seatid, status
                        FROM seating
                        WHERE seatid = ? AND ticketid = ?
                        LIMIT 1
                    ";
                    $stmt = $connection->prepare($checkSeat);
                    $stmt->bind_param("ii", $seatid, $ticketid);
                    $stmt->execute();
                    $checkResult = $stmt->get_result();
                    $seatRow = $checkResult->fetch_assoc();
                    $stmt->close();

                    if (!$seatRow || $seatRow['status'] !== 'available') {
                        throw new Exception('One or more selected seats are no longer available.');
                    }

                    $insertSeat = "
                        INSERT INTO booking_seats (bookingid, seatid)
                        VALUES (?, ?)
                    ";
                    $stmt = $connection->prepare($insertSeat);
                    $stmt->bind_param("ii", $bookingid, $seatid);
                    $stmt->execute();
                    $stmt->close();

                    $updateSeat = "
                        UPDATE seating
                        SET status = 'booked'
                        WHERE seatid = ?
                    ";
                    $stmt = $connection->prepare($updateSeat);
                    $stmt->bind_param("i", $seatid);
                    $stmt->execute();
                    $stmt->close();
                }

                $updateTicket = "
                    UPDATE tickets
                    SET availableseats = availableseats - ?
                    WHERE ticketid = ?
                ";
                $stmt = $connection->prepare($updateTicket);
                $stmt->bind_param("ii", $quantity, $ticketid);
                $stmt->execute();
                $stmt->close();

                mysqli_commit($connection);
                header("Location: bookings.php?success=1");
                exit();
            } catch (Throwable $e) {
                mysqli_rollback($connection);
                echo "<script>alert(" . json_encode($e->getMessage()) . ");</script>";
            }
        }
    } else {
        $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 0;

        if ($quantity <= 0) {
            echo "<script>alert('Please enter a valid quantity');</script>";
        } elseif ($quantity > (int) $ticket['availableseats']) {
            echo "<script>alert('Not enough available tickets');</script>";
        } else {
            $totalprice = (float) $ticket['price'] * $quantity;

            $insertBooking = "
                INSERT INTO bookings (userid, ticketid, quantity, totalprice, paymentstatus)
                VALUES (?, ?, ?, ?, 'pending')
            ";

            $stmt = $connection->prepare($insertBooking);
            $stmt->bind_param("iiid", $userid, $ticketid, $quantity, $totalprice);
            $stmt->execute();
            $stmt->close();

            $updateTicket = "
                UPDATE tickets
                SET availableseats = availableseats - ?
                WHERE ticketid = ?
            ";

            $stmt = $connection->prepare($updateTicket);
            $stmt->bind_param("ii", $quantity, $ticketid);
            $stmt->execute();
            $stmt->close();

            header("Location: bookings.php?success=1");
            exit();
        }
    }
}

include 'components/header.php';
?>

<style>
.book-seat-page {
    padding: 40px 0 60px;
}
.seat-map-wrap {
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
}
.seat-legend {
    display: flex;
    gap: 18px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}
.seat-legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
}
.seat-legend-box {
    width: 20px;
    height: 20px;
    border-radius: 6px;
    border: 1px solid #bbb;
}
.legend-available { background: #eeeeee; }
.legend-selected { background: #d11a2a; border-color: #d11a2a; }
.legend-booked { background: #777777; border-color: #777777; }
.track-banner {
    background: #b71c1c;
    color: #fff;
    text-align: center;
    padding: 14px;
    border-radius: 12px;
    font-weight: 700;
    margin-bottom: 24px;
}
.seat-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}
.seat-row-label {
    width: 28px;
    font-weight: 800;
    font-size: 20px;
}
.seat-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.seat {
    width: 48px;
    height: 48px;
    border: 1px solid #999;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    background: #eee;
    font-weight: 700;
}
.seat.booked {
    background: #777;
    color: #fff;
    cursor: not-allowed;
}
.seat.selected {
    background: #d11a2a !important;
    color: #fff !important;
    border-color: #d11a2a !important;
}
.summary-card {
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
}
.summary-price {
    font-size: 2rem;
    font-weight: 800;
}
</style>

<script>
function toggleSeat(el) {
    if (el.classList.contains("booked")) return;

    el.classList.toggle("selected");
    const checkbox = el.querySelector("input");
    checkbox.checked = !checkbox.checked;
    updateSummary();
}

function updateSummary() {
    const checked = document.querySelectorAll('input[name="seats[]"]:checked');
    const labels = [];
    checked.forEach((cb) => {
        labels.push(cb.dataset.label);
    });

    const summarySeats = document.getElementById('summarySeats');
    const summaryPrice = document.getElementById('summaryPrice');

    if (summarySeats) {
        summarySeats.textContent = labels.length ? labels.join(', ') : 'No seat selected';
    }

    if (summaryPrice) {
        const unitPrice = parseFloat(summaryPrice.dataset.unitPrice || '0');
        summaryPrice.textContent = '฿' + (unitPrice * labels.length).toLocaleString();
    }
}

function updateQuantitySummary() {
    const qtyInput = document.getElementById('quantity');
    const summaryQty = document.getElementById('summaryQty');
    const summaryPrice = document.getElementById('summaryPrice');

    if (!qtyInput || !summaryQty || !summaryPrice) return;

    let qty = parseInt(qtyInput.value || '0', 10);
    if (qty < 1) qty = 1;

    summaryQty.textContent = qty;
    const unitPrice = parseFloat(summaryPrice.dataset.unitPrice || '0');
    summaryPrice.textContent = '฿' + (unitPrice * qty).toLocaleString();
}
</script>

<div class="container book-seat-page">
    <h1 class="mb-3">Select Your Seat</h1>
    <h2 class="mb-4"><?php echo htmlspecialchars($ticket['racename']); ?></h2>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="seat-map-wrap">
                <div class="mb-3">
                    <strong>Category:</strong> <?php echo htmlspecialchars($ticket['category']); ?><br>
                    <strong>Section:</strong> <?php echo htmlspecialchars($ticket['section']); ?><br>
                    <strong>Seat Mode:</strong> <?php echo htmlspecialchars($ticket['seatmode']); ?>
                </div>

                <?php if ($isReserved): ?>
                    <?php if ($seatCount > 0): ?>
                        <div class="seat-legend">
                            <div class="seat-legend-item"><span class="seat-legend-box legend-available"></span> Available</div>
                            <div class="seat-legend-item"><span class="seat-legend-box legend-selected"></span> Selected</div>
                            <div class="seat-legend-item"><span class="seat-legend-box legend-booked"></span> Booked</div>
                        </div>

                        <div class="track-banner">🏁 Race Track View 🏁</div>

                        <form method="POST">
                            <?php foreach ($seats as $rowName => $rowSeats): ?>
                                <div class="seat-row">
                                    <div class="seat-row-label"><?php echo htmlspecialchars($rowName); ?></div>
                                    <div class="seat-grid">
                                        <?php foreach ($rowSeats as $seat): ?>
                                            <?php
                                            $seatLabel = $seat['rownumber'] . '-' . ltrim($seat['seatnumber'], '0');
                                            $seatStatus = strtolower($seat['status']);
                                            ?>
                                            <div class="seat <?php echo $seatStatus; ?>" onclick="toggleSeat(this)">
                                                <?php echo (int) $seat['seatnumber']; ?>
                                                <input
                                                    type="checkbox"
                                                    name="seats[]"
                                                    value="<?php echo (int) $seat['seatid']; ?>"
                                                    data-label="<?php echo htmlspecialchars($seatLabel); ?>"
                                                    hidden
                                                    <?php echo ($seatStatus === 'booked') ? 'disabled' : ''; ?>
                                                >
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-danger">Confirm Booking</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning mb-0">
                            No seat layout has been generated for this ticket yet. Please generate seats from the admin seating page first.
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <form method="POST">
                        <div class="form-group">
                            <label><strong>Quantity</strong></label>
                            <input
                                type="number"
                                class="form-control"
                                id="quantity"
                                name="quantity"
                                min="1"
                                max="<?php echo (int) $ticket['availableseats']; ?>"
                                value="1"
                                oninput="updateQuantitySummary()"
                                required
                            >
                            <small class="text-muted">
                                Available tickets: <?php echo (int) $ticket['availableseats']; ?>
                            </small>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-danger">Confirm Booking</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="summary-card">
                <h3 class="mb-4">Booking Summary</h3>

                <p><strong>Race:</strong><br><?php echo htmlspecialchars($ticket['racename']); ?></p>
                <p><strong>Category:</strong><br><?php echo htmlspecialchars($ticket['category']); ?></p>
                <p><strong>Section:</strong><br><?php echo htmlspecialchars($ticket['section']); ?></p>

                <?php if ($isReserved): ?>
                    <p><strong>Selected Seat(s):</strong><br><span id="summarySeats">No seat selected</span></p>
                <?php else: ?>
                    <p><strong>Quantity:</strong><br><span id="summaryQty">1</span></p>
                <?php endif; ?>

                <hr>

                <p><strong>Total Price</strong></p>
                <div class="summary-price" id="summaryPrice" data-unit-price="<?php echo (float) $ticket['price']; ?>">
                    <?php echo $isReserved ? '฿0' : '฿' . number_format((float) $ticket['price']); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'components/footer.php'; ?>