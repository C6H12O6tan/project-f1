<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit();
}

$ticketid = isset($_GET['ticketid']) ? (int) $_GET['ticketid'] : 0;
$error = '';

if ($ticketid <= 0) {
    $error = 'Invalid ticket ID.';
} else {
    $stmt = mysqli_prepare($connection, "
        SELECT *
        FROM tickets
        WHERE ticketid = ?
        LIMIT 1
    ");
    mysqli_stmt_bind_param($stmt, "i", $ticketid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $ticket = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$ticket) {
        $error = 'Ticket not found.';
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && $error === '') {
    $category = trim($_POST['category'] ?? '');
    $section = trim($_POST['section'] ?? '');
    $seatmode = trim($_POST['seatmode'] ?? 'general');
    $price = isset($_POST['price']) ? (float) $_POST['price'] : 0;
    $totalseats = isset($_POST['totalseats']) ? (int) $_POST['totalseats'] : 0;

    $allowedSeatModes = ['general', 'zoned', 'premium'];
    $soldSeats = (int) $ticket['totalseats'] - (int) $ticket['availableseats'];

    if ($category === '') {
        $error = 'Please enter a category.';
    } elseif ($section === '') {
        $error = 'Please enter a section.';
    } elseif (!in_array($seatmode, $allowedSeatModes, true)) {
        $error = 'Invalid seat mode.';
    } elseif ($price <= 0) {
        $error = 'Price must be greater than 0.';
    } elseif ($totalseats <= 0) {
        $error = 'Total seats must be greater than 0.';
    } elseif ($totalseats < $soldSeats) {
        $error = 'Total seats cannot be less than already sold seats (' . $soldSeats . ').';
    } else {
        $newAvailableSeats = $totalseats - $soldSeats;

        $updateStmt = mysqli_prepare($connection, "
            UPDATE tickets
            SET category = ?, section = ?, seatmode = ?, price = ?, totalseats = ?, availableseats = ?
            WHERE ticketid = ?
        ");

        mysqli_stmt_bind_param(
            $updateStmt,
            "sssdiii",
            $category,
            $section,
            $seatmode,
            $price,
            $totalseats,
            $newAvailableSeats,
            $ticketid
        );

        if (mysqli_stmt_execute($updateStmt)) {
            mysqli_stmt_close($updateStmt);
            header("Location: admin_manage_tickets.php?updated=1");
            exit();
        } else {
            $error = 'Failed to update ticket: ' . mysqli_error($connection);
        }

        mysqli_stmt_close($updateStmt);

        // โหลดข้อมูลใหม่หลัง update ไม่สำเร็จหรือกรณีต้องแสดงค่ากลับ
        $stmt = mysqli_prepare($connection, "
            SELECT *
            FROM tickets
            WHERE ticketid = ?
            LIMIT 1
        ");
        mysqli_stmt_bind_param($stmt, "i", $ticketid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $ticket = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
}

$current_page = 'admin_manage_tickets.php';
include 'components/admin_header.php';
?>

<header class="custom-header text-center py-4">
    <h1>Edit Ticket</h1>
    <p>แก้ไขประเภทตั๋วให้สอดคล้องกับจำนวนขายจริง</p>
</header>

<div class="container mt-5 mb-5">
    <div class="table-card p-4">
        <?php if ($error !== ''): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($ticketid > 0 && !empty($ticket)): ?>
            <form method="POST">
                <div class="form-group">
                    <label>Category</label>
                    <select class="form-control" name="category" required>
                        <option value="Walkabout" <?php echo (($ticket['category'] ?? '') === 'Walkabout') ? 'selected' : ''; ?>>Walkabout</option>
                        <option value="Grandstand" <?php echo (($ticket['category'] ?? '') === 'Grandstand') ? 'selected' : ''; ?>>Grandstand</option>
                        <option value="Hospitality" <?php echo (($ticket['category'] ?? '') === 'Hospitality') ? 'selected' : ''; ?>>Hospitality</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Section / Block</label>
                    <input
                        type="text"
                        class="form-control"
                        name="section"
                        value="<?php echo htmlspecialchars($ticket['section'] ?? ''); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Seat Mode</label>
                    <select class="form-control" name="seatmode" required>
                        <option value="general" <?php echo (($ticket['seatmode'] ?? '') === 'general') ? 'selected' : ''; ?>>General Admission</option>
                        <option value="zoned" <?php echo (($ticket['seatmode'] ?? '') === 'zoned') ? 'selected' : ''; ?>>Reserved Seating</option>
                        <option value="premium" <?php echo (($ticket['seatmode'] ?? '') === 'premium') ? 'selected' : ''; ?>>Premium Reserved Seating</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Price</label>
                    <input
                        type="number"
                        class="form-control"
                        name="price"
                        min="1"
                        step="0.01"
                        value="<?php echo htmlspecialchars($ticket['price'] ?? ''); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Total Seats</label>
                    <input
                        type="number"
                        class="form-control"
                        name="totalseats"
                        min="1"
                        step="1"
                        value="<?php echo htmlspecialchars($ticket['totalseats'] ?? ''); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Already Sold</label>
                    <input
                        type="text"
                        class="form-control"
                        value="<?php echo (int)($ticket['totalseats'] ?? 0) - (int)($ticket['availableseats'] ?? 0); ?>"
                        readonly
                    >
                </div>

                <div class="form-group">
                    <label>Current Available Seats</label>
                    <input
                        type="text"
                        class="form-control"
                        value="<?php echo (int)($ticket['availableseats'] ?? 0); ?>"
                        readonly
                    >
                </div>

                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="admin_manage_tickets.php" class="btn btn-secondary">Back</a>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php include 'components/admin_footer.php'; ?>