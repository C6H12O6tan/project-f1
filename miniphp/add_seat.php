<?php
session_start();
include 'db.php';

$zone_query = "SELECT DISTINCT section FROM seating"; 
$zone_result = mysqli_query($connection, $zone_query);

$ticket_query = "SELECT ticketid FROM tickets";
$ticket_result = mysqli_query($connection, $ticket_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ticketid = $_POST['ticketid'];
    $section = $_POST['section'];
    $rownumber = $_POST['rownumber'];
    $seatnumber = $_POST['seatnumber'];
    $status = $_POST['status'];

    $query = "INSERT INTO seating (ticketid, section, rownumber, seatnumber, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("issss", $ticketid, $section, $rownumber, $seatnumber, $status);

    if ($stmt->execute()) {
        header("Location: admin_seating.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Add Seat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>เพิ่มที่นั่งใหม่</h2>
        <form method="POST">
            <div class="form-group">
                <label>เลือก Ticket ID</label>
                <select class="form-control" name="ticketid" required>
                    <option value="">-- กรุณาเลือก --</option>
                    <?php while ($row = mysqli_fetch_assoc($ticket_result)) { ?>
                        <option value="<?= $row['ticketid'] ?>"><?= $row['ticketid'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>โซน</label>
                <select class="form-control" name="section" required>
                    <option value="">-- กรุณาเลือก --</option>
                    <?php while ($row = mysqli_fetch_assoc($zone_result)) { ?>
                        <option value="<?= $row['section'] ?>"><?= $row['section'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>แถว</label>
                <input type="text" class="form-control" name="rownumber" required>
            </div>
            <div class="form-group">
                <label>เลขที่นั่ง</label>
                <input type="text" class="form-control" name="seatnumber" required>
            </div>
            <div class="form-group">
                <label>สถานะ</label>
                <select class="form-control" name="status" required>
                    <option value="available">available</option>
                    <option value="booked">booked</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">+ เพิ่มที่นั่ง</button>
            <a href="admin_seating.php" class="btn btn-secondary">กลับ</a>
        </form>
    </div>
</body>
</html>
