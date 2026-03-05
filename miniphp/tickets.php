<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';

$query = "SELECT * FROM tickets";
$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>F1 Ticket Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/ticket.css">

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

    <!-- Navbar -->
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
                    <li class="nav-item"><a class="nav-link active" href="tickets.php">Tickets</a></li>
                    <li class="nav-item"><a class="nav-link" href="bookings.php">Reservation</a></li>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                👤 <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="profile.php">โปรไฟล์</a>
                                <a class="dropdown-item text-danger" href="logout.php">ออกจากระบบ</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Sign up</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="custom-header text-center py-4">
        <h1>เลือกซื้อตั๋ว Formula 1</h1>
        <p>เลือกที่นั่งของคุณและจองตอนนี้</p>
    </header>

    <!-- Container -->
    <div class="container mt-5">
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card p-3 shadow">
                        <h4 class="text-custom-red">ประเภท: <?php echo htmlspecialchars($row['category']); ?></h4>
                        <p><strong>โซน:</strong> <?php echo htmlspecialchars($row['section']); ?></p>
                        <p><strong>ราคา:</strong> <?php echo number_format($row['price']); ?> บาท</p>
                        <p><strong>ที่นั่งว่าง:</strong> <?php echo htmlspecialchars($row['availableseats']); ?> ที่</p>
                        <a href="book.php?ticketid=<?php echo $row['ticketid']; ?>" class="btn custom-btn">จองเลย</a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="custom-footer text-center py-3 mt-5">
        <p>&copy; 2025 F1 Ticket Management | All Rights Reserved By ME</p>
    </footer>

</body>
</html>
