<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['bookingid'])) {
    header('Location: bookings.php');
    exit();
}

$bookingid = intval($_GET['bookingid']);
$user_id = $_SESSION['user_id'];

mysqli_begin_transaction($connection);

$query = "SELECT * FROM bookings WHERE bookingid = ? AND userid = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "ii", $bookingid, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$booking) {
    mysqli_rollback($connection);
    echo "<script>alert('❌ ไม่พบข้อมูลการจอง!'); window.location.href='bookings.php';</script>";
    exit();
}

$ticketid = $booking['ticketid'];
$quantity = $booking['quantity'];
$seat_numbers = isset($booking['seat_numbers']) ? $booking['seat_numbers'] : "";

if (!empty($seat_numbers)) {
    $seatNumbersArr = explode(", ", $seat_numbers);
    $seatNumbersStr = "'" . implode("', '", $seatNumbersArr) . "'";
    
    $updateSeatsQuery = "UPDATE seating 
                         SET status = 'available' 
                         WHERE ticketid = ? 
                           AND seatnumber IN ($seatNumbersStr)";
    $stmt = mysqli_prepare($connection, $updateSeatsQuery);
    mysqli_stmt_bind_param($stmt, "i", $ticketid);
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_rollback($connection);
        echo "<script>alert('❌ เกิดข้อผิดพลาดในการคืนสถานะที่นั่ง: " . mysqli_error($connection) . "'); 
                     window.location.href='bookings.php';</script>";
        exit();
    }
    mysqli_stmt_close($stmt);
}

$updateTicketQuery = "UPDATE tickets 
                      SET availableseats = availableseats + ? 
                      WHERE ticketid = ?";
$stmt = mysqli_prepare($connection, $updateTicketQuery);
mysqli_stmt_bind_param($stmt, "ii", $quantity, $ticketid);
if (!mysqli_stmt_execute($stmt)) {
    mysqli_rollback($connection);
    echo "<script>alert('❌ เกิดข้อผิดพลาดในการคืนจำนวนที่นั่ง: " . mysqli_error($connection) . "'); 
                 window.location.href='bookings.php';</script>";
    exit();
}
mysqli_stmt_close($stmt);

$updateBookingQuery = "UPDATE bookings 
                       SET paymentstatus = 'cancelled' 
                       WHERE bookingid = ?";
$stmt = mysqli_prepare($connection, $updateBookingQuery);
mysqli_stmt_bind_param($stmt, "i", $bookingid);
if (!mysqli_stmt_execute($stmt)) {
    mysqli_rollback($connection);
    echo "<script>alert('❌ เกิดข้อผิดพลาดในการอัปเดตสถานะ: " . mysqli_error($connection) . "'); 
                 window.location.href='bookings.php';</script>";
    exit();
}
mysqli_stmt_close($stmt);

mysqli_commit($connection);

echo "<script>
        alert('✅ ยกเลิกการจองสำเร็จ! ที่นั่งของคุณถูกคืนเรียบร้อยแล้ว');
        window.location.href='bookings.php';
      </script>";

mysqli_close($connection);
?>
