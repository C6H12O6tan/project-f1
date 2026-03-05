<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['bookingid'])) {
    $bookingid = $_GET['bookingid'];

    $query = "SELECT * FROM bookings WHERE bookingid = '$bookingid'";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $booking = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('ไม่พบข้อมูลการจองที่ต้องการแก้ไข'); window.location.href='admin_bookings.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ไม่มีการระบุ Booking ID'); window.location.href='admin_bookings.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'];

    $update_query = "UPDATE bookings SET paymentstatus='$status' WHERE bookingid='$bookingid'";

    if (mysqli_query($connection, $update_query)) {
        echo "<script>alert('อัปเดตสถานะสำเร็จ!'); window.location.href='admin_bookings.php';</script>";
        exit();
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Edit Booking</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>แก้ไขสถานะการจอง</h2>
        <form method="POST">
            <div class="form-group">
                <label>สถานะ</label>
                <select class="form-control" name="status" required>
                    <option value="pending" <?php echo (isset($booking['paymentstatus']) && $booking['paymentstatus'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="paid" <?php echo (isset($booking['paymentstatus']) && $booking['paymentstatus'] == 'paid') ? 'selected' : ''; ?>>Paid</option>
                    <option value="cancelled" <?php echo (isset($booking['paymentstatus']) && $booking['paymentstatus'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
        </form>
    </div>
</body>
</html>
