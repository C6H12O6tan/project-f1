<?php
session_start();
include 'db.php';

if (!isset($_GET['seatid'])) {
    header("Location: admin_seating.php");
    exit();
}

$seatid = $_GET['seatid'];

$query = "SELECT * FROM seating WHERE seatid = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $seatid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: admin_seating.php");
    exit();
}

$seat = $result->fetch_assoc();

$zone_query = "SELECT DISTINCT section FROM seating";
$zone_result = mysqli_query($connection, $zone_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $section = $_POST['section'];
    $row = $_POST['row'];
    $seat_number = $_POST['seat_number'];
    $status = $_POST['status'];

    $update_query = "UPDATE seating SET section = ?, rownumber = ?, seatnumber = ?, status = ? WHERE seatid = ?";
    $stmt = $connection->prepare($update_query);
    $stmt->bind_param("ssssi", $section, $row, $seat_number, $status, $seatid);

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
    <title>Edit Seat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>แก้ไขที่นั่ง</h2>
        <form method="POST">
            <div class="form-group">
                <label>โซน</label>
                <select class="form-control" name="section" required>
                    <?php while ($row = mysqli_fetch_assoc($zone_result)) { ?>
                        <option value="<?= $row['section'] ?>" <?= ($seat['section'] == $row['section']) ? 'selected' : '' ?>>
                            <?= $row['section'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>แถว</label>
                <input type="text" class="form-control" name="row" value="<?= $seat['rownumber'] ?>" required>
            </div>
            <div class="form-group">
                <label>เลขที่นั่ง</label>
                <input type="text" class="form-control" name="seat_number" value="<?= $seat['seatnumber'] ?>" required>
            </div>
            <div class="form-group">
                <label>สถานะ</label>
                <select class="form-control" name="status" required>
                    <option value="available" <?= ($seat['status'] == 'available') ? 'selected' : '' ?>>available</option>
                    <option value="booked" <?= ($seat['status'] == 'booked') ? 'selected' : '' ?>>booked</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
            <a href="admin_seating.php" class="btn btn-secondary">กลับ</a>
        </form>
    </div>
</body>
</html>
