<?php
$connection = mysqli_connect("db", "app", "apppass", "ticketf1");

if (!$connection) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . mysqli_connect_error());
}

mysqli_query($connection, "SET NAMES 'utf8'");
date_default_timezone_set('Asia/Bangkok');
?>