<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['ticketid'])) {
    $ticketid = $_GET['ticketid'];

    $query = "DELETE FROM tickets WHERE ticketid='$ticketid'";
    if (mysqli_query($connection, $query)) {
        header("Location: admin_manage_tickets.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($connection);
    }
}
?>
