<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ticketid = isset($_POST['ticketid']) ? (int) $_POST['ticketid'] : 0;
    $rowStart = strtoupper(trim($_POST['row_start'] ?? ''));
    $rowEnd = strtoupper(trim($_POST['row_end'] ?? ''));
    $seatFrom = isset($_POST['seat_from']) ? (int) $_POST['seat_from'] : 0;
    $seatTo = isset($_POST['seat_to']) ? (int) $_POST['seat_to'] : 0;
    $status = trim($_POST['status'] ?? 'available');

    if ($ticketid <= 0) {
        $error = 'Please select a ticket.';
    } elseif ($rowStart === '' || $rowEnd === '' || strlen($rowStart) !== 1 || strlen($rowEnd) !== 1) {
        $error = 'Please enter valid row range, for example A to F.';
    } elseif (ord($rowStart) > ord($rowEnd)) {
        $error = 'Row start must be before or equal to row end.';
    } elseif ($seatFrom <= 0 || $seatTo <= 0 || $seatTo < $seatFrom) {
        $error = 'Please enter valid seat range.';
    } elseif (!in_array($status, ['available', 'booked'], true)) {
        $error = 'Invalid seat status.';
    } else {
        $ticketStmt = mysqli_prepare($connection, "
            SELECT ticketid, section, seatmode
            FROM tickets
            WHERE ticketid = ?
            LIMIT 1
        ");
        mysqli_stmt_bind_param($ticketStmt, "i", $ticketid);
        mysqli_stmt_execute($ticketStmt);
        $ticketResult = mysqli_stmt_get_result($ticketStmt);
        $ticket = mysqli_fetch_assoc($ticketResult);
        mysqli_stmt_close($ticketStmt);

        if (!$ticket) {
            $error = 'Ticket not found.';
        } elseif (!in_array($ticket['seatmode'], ['zoned', 'premium'], true)) {
            $error = 'You can only generate seats for zoned or premium tickets.';
        } else {
            $created = 0;
            $skipped = 0;
            $section = $ticket['section'];

            for ($rowCode = ord($rowStart); $rowCode <= ord($rowEnd); $rowCode++) {
                $rowLetter = chr($rowCode);

                for ($seatNumber = $seatFrom; $seatNumber <= $seatTo; $seatNumber++) {
                    $seatValue = str_pad((string) $seatNumber, 3, '0', STR_PAD_LEFT);

                    $checkStmt = mysqli_prepare($connection, "
                        SELECT seatid
                        FROM seating
                        WHERE ticketid = ? AND rownumber = ? AND seatnumber = ?
                        LIMIT 1
                    ");
                    mysqli_stmt_bind_param($checkStmt, "iss", $ticketid, $rowLetter, $seatValue);
                    mysqli_stmt_execute($checkStmt);
                    $checkResult = mysqli_stmt_get_result($checkStmt);
                    $exists = mysqli_fetch_assoc($checkResult);
                    mysqli_stmt_close($checkStmt);

                    if ($exists) {
                        $skipped++;
                        continue;
                    }

                    $insertStmt = mysqli_prepare($connection, "
                        INSERT INTO seating (ticketid, section, rownumber, seatnumber, status)
                        VALUES (?, ?, ?, ?, ?)
                    ");
                    mysqli_stmt_bind_param($insertStmt, "issss", $ticketid, $section, $rowLetter, $seatValue, $status);

                    if (mysqli_stmt_execute($insertStmt)) {
                        $created++;
                    }

                    mysqli_stmt_close($insertStmt);
                }
            }

            $success = 'Created ' . $created . ' seat(s). Skipped ' . $skipped . ' existing seat(s).';
        }
    }
}

$ticketsResult = mysqli_query($connection, "
    SELECT ticketid, category, section, seatmode
    FROM tickets
    WHERE seatmode IN ('zoned', 'premium')
    ORDER BY ticketid ASC
");

$current_page = 'admin_seating.php';
include 'components/admin_header.php';
?>

<header class="custom-header text-center py-4">
    <h1>Add Seats</h1>
    <p>สร้างที่นั่งแบบเป็นชุดสำหรับตั๋วที่ต้องเลือกที่นั่งจริง</p>
</header>

<div class="container mt-5 mb-5">
    <div class="table-card p-4">
        <?php if ($error !== ''): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success !== ''): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Ticket</label>
                <select class="form-control" name="ticketid" required>
                    <option value="">-- Select Ticket --</option>
                    <?php if ($ticketsResult && mysqli_num_rows($ticketsResult) > 0): ?>
                        <?php while ($ticket = mysqli_fetch_assoc($ticketsResult)): ?>
                            <option value="<?php echo (int) $ticket['ticketid']; ?>">
                                #<?php echo (int) $ticket['ticketid']; ?> -
                                <?php echo htmlspecialchars($ticket['category'] . ' / ' . $ticket['section'] . ' / ' . $ticket['seatmode']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Row Start</label>
                    <input type="text" class="form-control" name="row_start" maxlength="1" placeholder="A" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Row End</label>
                    <input type="text" class="form-control" name="row_end" maxlength="1" placeholder="F" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Seat From</label>
                    <input type="number" class="form-control" name="seat_from" min="1" value="1" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Seat To</label>
                    <input type="number" class="form-control" name="seat_to" min="1" value="12" required>
                </div>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select class="form-control" name="status" required>
                    <option value="available">available</option>
                    <option value="booked">booked</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Generate Seats</button>
            <a href="admin_seating.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>

<?php include 'components/admin_footer.php'; ?>