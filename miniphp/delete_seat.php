<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['seatid'])) {
    $seatid = $_GET['seatid'];
    $query = "DELETE FROM seating WHERE seatid = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $seatid);

    if ($stmt->execute()) {
        header("Location: admin_seating.php?success=deleted");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    header("Location: admin_seating.php");
    exit();
}
?>
