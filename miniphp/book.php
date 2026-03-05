<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['ticketid'])) {
    echo "<script>alert('ไม่พบข้อมูลตั๋ว!'); window.location='tickets.php';</script>";
    exit;
}

$ticketid = intval($_GET['ticketid']);
$query = "SELECT * FROM tickets WHERE ticketid = $ticketid";
$result = mysqli_query($connection, $query);
$ticket = mysqli_fetch_assoc($result);

if (!$ticket) {
    echo "<script>alert('ตั๋วนี้ไม่มีอยู่ในระบบ!'); window.location='tickets.php';</script>";
    exit;
}

$success = false;
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quantity = intval($_POST['quantity']);

    if ($quantity <= 0 || $quantity > $ticket['availableseats']) {
        $error_message = "จำนวนที่นั่งไม่ถูกต้อง";
    } else {
        $totalprice = $ticket['price'] * $quantity;
        $paymentstatus = 'pending';

        $seatQuery = "
            SELECT seatid, seatnumber, section 
            FROM seating 
            WHERE ticketid = $ticketid 
              AND status = 'available' 
            ORDER BY seatnumber ASC 
            LIMIT $quantity
        ";
        $seatResult = mysqli_query($connection, $seatQuery);

        if (mysqli_num_rows($seatResult) < $quantity) {
            $error_message = "ที่นั่งไม่เพียงพอ!";
        } else {
            $seatIDs = [];
            $seatInfo = [];
            while ($seat = mysqli_fetch_assoc($seatResult)) {
                $seatIDs[] = $seat['seatid'];
                $seatInfo[] = $seat['section'] . " - " . $seat['seatnumber'];
            }

            $seatInfoStr = implode(', ', $seatInfo);

            $insertQuery = "
                INSERT INTO bookings (userid, ticketid, quantity, totalprice, paymentstatus) 
                VALUES ($user_id, $ticketid, $quantity, $totalprice, '$paymentstatus')
            ";

            if (mysqli_query($connection, $insertQuery)) {
                foreach ($seatIDs as $seatID) {
                    $updateSeatQuery = "UPDATE seating SET status = 'booked' WHERE seatid = $seatID";
                    mysqli_query($connection, $updateSeatQuery);
                }

                $updateTicketQuery = "
                    UPDATE tickets 
                    SET availableseats = availableseats - $quantity 
                    WHERE ticketid = $ticketid
                ";
                mysqli_query($connection, $updateTicketQuery);

                $success = true;
            } else {
                $error_message = mysqli_error($connection);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จองตั๋ว | F1 Ticket Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/ticket.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">F1 Ticket Management</a>
        </div>
    </nav>

    <header class="custom-header text-center py-4">
        <h1>จองตั๋ว Formula 1</h1>
    </header>

    <div class="container mt-5">
        <div class="card p-4 shadow custom-card">
            <h4 class="text-custom">ประเภท: <?php echo $ticket['category']; ?></h4>
            <p><strong>โซน:</strong> <?php echo $ticket['section']; ?></p>
            <p><strong>ราคา:</strong> <?php echo number_format($ticket['price']); ?> บาท</p>
            <p><strong>ที่นั่งว่าง:</strong> <?php echo $ticket['availableseats']; ?> ที่</p>

            <form method="POST">
                <div class="form-group">
                    <label for="quantity">จำนวนตั๋ว:</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" 
                           required min="1" max="<?php echo $ticket['availableseats']; ?>">
                </div>
                <button type="submit" class="btn custom-btn">ยืนยันการจอง</button>
            </form>
        </div>
    </div>

    <footer class="custom-footer text-center py-3 mt-5">
        <p>&copy; 2025 F1 Ticket Management | All Rights Reserved By ME</p>
    </footer>

<?php if ($success): ?>
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-modal-red">
            <div class="modal-header bg-custom-red">
                <h5 class="modal-title" id="successModalLabel">✅ จองตั๋วสำเร็จ!</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-ticket-alt modal-icon"></i>
                <h5>ที่นั่งของคุณคือ: <strong class="text-custom-red"><?= $seatInfoStr ?></strong></h5>
            </div>
            <div class="modal-footer">
                <a href="bookings.php" class="btn btn-custom-red">ดูรายการจอง</a>
            </div>
        </div>
    </div>
</div>
    <script>
        $(document).ready(function() {
            $('#successModal').modal('show');
        });
    </script>
<?php elseif (!empty($error_message)): ?>
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal-danger">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="errorModalLabel">
                        <i class="fas fa-exclamation-triangle"></i> เกิดข้อผิดพลาด
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <h5 class="text-danger"><?= $error_message ?></h5>
                </div>
                <div class="modal-footer">
                    <a href="book.php?ticketid=<?= $ticketid ?>" class="btn btn-danger font-weight-bold">ลองอีกครั้ง</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#errorModal').modal('show');
        });
    </script>
<?php endif; ?>
    
</body>

</html>
