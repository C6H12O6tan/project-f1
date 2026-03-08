<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit();
}

$seatid = isset($_GET['seatid']) ? (int) $_GET['seatid'] : 0;

if ($seatid <= 0) {
    header("Location: admin_seating.php");
    exit();
}

$error = '';

$query = "SELECT * FROM seating WHERE seatid = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $seatid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$seat = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$seat) {
    header("Location: admin_seating.php");
    exit();
}

$zoneQuery = mysqli_query($connection, "SELECT DISTINCT section FROM seating ORDER BY section ASC");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $section = trim($_POST['section'] ?? '');
    $row = strtoupper(trim($_POST['row'] ?? ''));
    $seat_number_raw = isset($_POST['seat_number']) ? (int) $_POST['seat_number'] : 0;
    $status = trim($_POST['status'] ?? '');

    if ($section === '') {
        $error = 'Please select a section.';
    } elseif ($row === '' || strlen($row) !== 1) {
        $error = 'Please enter a valid row.';
    } elseif ($seat_number_raw <= 0) {
        $error = 'Please enter a valid seat number.';
    } elseif (!in_array($status, ['available', 'booked'], true)) {
        $error = 'Invalid seat status.';
    } else {
        $seat_number = str_pad((string) $seat_number_raw, 3, '0', STR_PAD_LEFT);

        $update_query = "
            UPDATE seating
            SET section = ?, rownumber = ?, seatnumber = ?, status = ?
            WHERE seatid = ?
        ";
        $stmt = mysqli_prepare($connection, $update_query);
        mysqli_stmt_bind_param($stmt, "ssssi", $section, $row, $seat_number, $status, $seatid);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: admin_seating.php?updated=1");
            exit();
        } else {
            $error = "Failed to update seat: " . mysqli_error($connection);
        }

        mysqli_stmt_close($stmt);
    }
}

$current_page = 'admin_seating.php';
include 'components/admin_header.php';
?>

<header class="custom-header text-center py-4">
    <h1>Edit Seat</h1>
    <p>แก้ไขข้อมูลที่นั่ง</p>
</header>

<div class="container mt-5 mb-5">
    <div class="table-card p-4">
        <?php if ($error !== ''): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Section</label>
                <select class="form-control" name="section" required>
                    <?php while ($rowItem = mysqli_fetch_assoc($zoneQuery)): ?>
                        <option value="<?php echo htmlspecialchars($rowItem['section']); ?>"
                            <?php echo ($seat['section'] === $rowItem['section']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($rowItem['section']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Row</label>
                <input type="text" class="form-control" name="row" maxlength="1" value="<?php echo htmlspecialchars($seat['rownumber']); ?>" required>
            </div>

            <div class="form-group">
                <label>Seat Number</label>
                <input type="number" class="form-control" name="seat_number" min="1" value="<?php echo (int) $seat['seatnumber']; ?>" required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select class="form-control" name="status" required>
                    <option value="available" <?php echo ($seat['status'] === 'available') ? 'selected' : ''; ?>>available</option>
                    <option value="booked" <?php echo ($seat['status'] === 'booked') ? 'selected' : ''; ?>>booked</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="admin_seating.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>

<?php include 'components/admin_footer.php'; ?>