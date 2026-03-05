<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['bookingid'])) {
    $bookingid = $_GET['bookingid'];

    $check_query = "SELECT * FROM bookings WHERE bookingid='$bookingid'";
    $check_result = mysqli_query($connection, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $delete_query = "DELETE FROM bookings WHERE bookingid='$bookingid'";
        
        if (mysqli_query($connection, $delete_query)) {
            echo "<script>
                alert('การจองถูกลบเรียบร้อยแล้ว!');
                window.location.href = 'admin_bookings.php';
            </script>";
            exit();
        } else {
            echo "<script>
                alert('เกิดข้อผิดพลาดในการลบการจอง: " . mysqli_error($connection) . "');
                window.history.back();
            </script>";
        }
    } else {
        echo "<script>
            alert('ไม่พบข้อมูลการจอง!');
            window.history.back();
        </script>";
    }
} else {
    echo "<script>
        alert('ไม่มีการระบุ Booking ID!');
        window.history.back();
    </script>";
}
?>
