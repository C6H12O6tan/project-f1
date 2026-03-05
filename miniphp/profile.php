<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id='$user_id'";
$result = mysqli_query($connection, $query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "<script>alert('ไม่พบข้อมูลผู้ใช้'); window.location='login.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>โปรไฟล์ | F1 Ticket Management</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark custom-navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">F1 Ticket Management</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="tickets.php">Tickets</a></li>
                    <li class="nav-item"><a class="nav-link" href="bookings.php">Reservation</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center text-custom-red">โปรไฟล์ของฉัน</h2>
        <div class="card p-4">
            <p><strong>ชื่อ:</strong> <?php echo $user['first_name'] . " " . $user['last_name']; ?></p>
            <p><strong>อีเมล:</strong> <?php echo $user['email']; ?></p>
            <p><strong>สิทธิ์การใช้งาน:</strong>
                <?php echo isset($user['user_type']) ? ucfirst($user['user_type']) : 'ไม่ระบุ'; ?></p>
            <a href="logout.php" class="btn custom-btn">ออกจากระบบ</a>
        </div>
    </div>

</body>

</html>