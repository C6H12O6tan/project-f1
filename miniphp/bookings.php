<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT b.bookingid, t.category, t.section, b.quantity, b.totalprice, b.paymentstatus
          FROM bookings b
          JOIN tickets t ON b.ticketid = t.ticketid
          WHERE b.userid = ?
          ORDER BY b.bookingid ASC";

$stmt = $connection->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Bookings | F1 Ticket Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bookings.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark custom-navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">F1 Ticket Management</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="tickets.php">Tickets</a></li>
                    <li class="nav-item"><a class="nav-link active" href="bookings.php">Reservation</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            👤 <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="profile.php">โปรไฟล์</a>
                            <a class="dropdown-item text-danger" href="logout.php">ออกจากระบบ</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center">การจองของฉัน</h2>
        <div class="row justify-content-center">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="card w-75 my-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">การจองที่ #<?php echo $row['bookingid']; ?></h5>
                        <p class="card-text">
                            <strong>ประเภทตั๋ว:</strong> <?php echo $row['category']; ?><br>
                            <strong>โซน:</strong> <?php echo $row['section']; ?><br>
                            <strong>จำนวน:</strong> <?php echo $row['quantity']; ?><br>
                            <strong>ราคารวม:</strong> <?php echo number_format($row['totalprice'], 2); ?> บาท<br>
                            <strong>สถานะชำระเงิน:</strong>
                            <?php
                            if ($row['paymentstatus'] == 'pending') {
                                echo '<span class="badge badge-warning">รอการชำระเงิน</span>';
                            } elseif ($row['paymentstatus'] == 'paid') {
                                echo '<span class="badge badge-success">ชำระเงินแล้ว</span>';
                            } elseif ($row['paymentstatus'] == 'cancelled') {
                                echo '<span class="badge badge-danger">ยกเลิกการจองแล้ว</span>';
                            }
                            ?>
                        </p>
                        <?php if ($row['paymentstatus'] == 'pending'): ?>
                            <a href="payment.php?bookingid=<?php echo $row['bookingid']; ?>"
                                class="btn btn-success">ยืนยันการจอง (ชำระเงิน)</a>
                            <a href="cancel_booking.php?bookingid=<?php echo $row['bookingid']; ?>"
                                onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการยกเลิกการจองนี้?');"
                                class="btn btn-danger">ยกเลิกการจอง</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <footer class="custom-footer text-center py-3 mt-5">
        <p>&copy; 2025 F1 Ticket Management | All Rights Reserved</p>
    </footer>
</body>
</html>
