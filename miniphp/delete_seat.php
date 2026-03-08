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

$stmt = mysqli_prepare($connection, "DELETE FROM seating WHERE seatid = ?");
mysqli_stmt_bind_param($stmt, "i", $seatid);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: admin_seating.php?deleted=1");
    exit();
} else {
    mysqli_stmt_close($stmt);
    echo "Error deleting seat.";
}
?>