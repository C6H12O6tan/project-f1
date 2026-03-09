<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit();
}

$booking = null;
$bookingId = isset($_GET['bookingid']) ? (int) $_GET['bookingid'] : 0;

if ($bookingId <= 0) {
    echo "<script>alert('ไม่มีการระบุ Booking ID'); window.location.href='admin_bookings.php';</script>";
    exit();
}

/* ดึงข้อมูล booking */
$selectSql = "
    SELECT 
        b.bookingid,
        b.userid,
        b.ticketid,
        b.quantity,
        b.totalprice,
        b.paymentstatus,
        b.bookingdate,
        b.payment_date,
        u.first_name,
        u.last_name,
        u.email,
        t.category,
        t.section
    FROM bookings b
    LEFT JOIN users u ON b.userid = u.user_id
    LEFT JOIN tickets t ON b.ticketid = t.ticketid
    WHERE b.bookingid = ?
    LIMIT 1
";

$selectStmt = mysqli_prepare($connection, $selectSql);

if (!$selectStmt) {
    echo "<script>alert('เกิดข้อผิดพลาดในการโหลดข้อมูล'); window.location.href='admin_bookings.php';</script>";
    exit();
}

mysqli_stmt_bind_param($selectStmt, "i", $bookingId);
mysqli_stmt_execute($selectStmt);
$result = mysqli_stmt_get_result($selectStmt);
$booking = mysqli_fetch_assoc($result);
mysqli_stmt_close($selectStmt);

if (!$booking) {
    echo "<script>alert('ไม่พบข้อมูลการจองที่ต้องการแก้ไข'); window.location.href='admin_bookings.php';</script>";
    exit();
}

$errorMessage = '';
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';
    $allowedStatuses = ['pending', 'paid', 'cancelled'];

    if (!in_array($status, $allowedStatuses, true)) {
        $errorMessage = 'สถานะที่เลือกไม่ถูกต้อง';
    } else {
        $oldStatus = strtolower($booking['paymentstatus'] ?? 'pending');
        $quantity = (int) ($booking['quantity'] ?? 0);
        $ticketId = (int) ($booking['ticketid'] ?? 0);

        mysqli_begin_transaction($connection);

        try {
            $paymentDate = null;

            if ($status === 'paid') {
                $paymentDate = date('Y-m-d H:i:s');
            }

            if ($status === 'paid') {
                $updateSql = "
                    UPDATE bookings
                    SET paymentstatus = ?, payment_date = ?
                    WHERE bookingid = ?
                ";
                $updateStmt = mysqli_prepare($connection, $updateSql);
                if (!$updateStmt) {
                    throw new Exception('prepare update failed');
                }

                mysqli_stmt_bind_param($updateStmt, "ssi", $status, $paymentDate, $bookingId);
            } else {
                $updateSql = "
                    UPDATE bookings
                    SET paymentstatus = ?
                    WHERE bookingid = ?
                ";
                $updateStmt = mysqli_prepare($connection, $updateSql);
                if (!$updateStmt) {
                    throw new Exception('prepare update failed');
                }

                mysqli_stmt_bind_param($updateStmt, "si", $status, $bookingId);
            }

            if (!mysqli_stmt_execute($updateStmt)) {
                mysqli_stmt_close($updateStmt);
                throw new Exception('execute update failed');
            }
            mysqli_stmt_close($updateStmt);

            /*
              ถ้าสถานะเดิมเป็น paid แล้วเปลี่ยนเป็น cancelled
              สามารถคืนที่นั่งกลับได้
              เปิดใช้เฉพาะกรณีที่ระบบคุณลด availableseats ตอนจ่ายเงินจริงแล้วเท่านั้น
            */
            if ($oldStatus === 'paid' && $status === 'cancelled' && $ticketId > 0 && $quantity > 0) {
                $seatSql = "
                    UPDATE tickets
                    SET availableseats = availableseats + ?
                    WHERE ticketid = ?
                ";
                $seatStmt = mysqli_prepare($connection, $seatSql);
                if (!$seatStmt) {
                    throw new Exception('prepare seat restore failed');
                }

                mysqli_stmt_bind_param($seatStmt, "ii", $quantity, $ticketId);

                if (!mysqli_stmt_execute($seatStmt)) {
                    mysqli_stmt_close($seatStmt);
                    throw new Exception('execute seat restore failed');
                }

                mysqli_stmt_close($seatStmt);
            }

            mysqli_commit($connection);

            echo "<script>alert('อัปเดตสถานะสำเร็จ!'); window.location.href='admin_bookings.php';</script>";
            exit();
        } catch (Exception $e) {
            mysqli_rollback($connection);
            $errorMessage = 'ไม่สามารถอัปเดตสถานะได้';
        }
    }
}

$current_page = 'admin_bookings.php';
include 'components/admin_header.php';
?>

<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h2 class="mb-4">แก้ไขสถานะการจอง</h2>

                    <?php if ($errorMessage !== ''): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($errorMessage); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($successMessage !== ''): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($successMessage); ?>
                        </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Booking ID</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($booking['bookingid']); ?>" disabled>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">User</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars(trim(($booking['first_name'] ?? '') . ' ' . ($booking['last_name'] ?? ''))); ?>"
                                    disabled
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($booking['email'] ?? '-'); ?>" disabled>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Ticket</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars(($booking['category'] ?? '-') . ' / ' . ($booking['section'] ?? '-')); ?>"
                                    disabled
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Quantity</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($booking['quantity'] ?? '0'); ?>" disabled>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Total Price</label>
                                <input type="text" class="form-control" value="<?php echo number_format((float) ($booking['totalprice'] ?? 0), 2); ?>" disabled>
                            </div>
                        </div>
                    </div>

                    <form method="POST">
                        <div class="form-group mb-4">
                            <label for="status" class="form-label fw-bold">สถานะ</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="pending" <?php echo (($booking['paymentstatus'] ?? '') === 'pending') ? 'selected' : ''; ?>>
                                    Pending
                                </option>
                                <option value="paid" <?php echo (($booking['paymentstatus'] ?? '') === 'paid') ? 'selected' : ''; ?>>
                                    Paid
                                </option>
                                <option value="cancelled" <?php echo (($booking['paymentstatus'] ?? '') === 'cancelled') ? 'selected' : ''; ?>>
                                    Cancelled
                                </option>
                            </select>
                        </div>

                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-danger">
                                บันทึกการเปลี่ยนแปลง
                            </button>
                            <a href="admin_bookings.php" class="btn btn-outline-secondary">
                                ย้อนกลับ
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'components/admin_footer.php'; ?>