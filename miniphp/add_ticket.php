<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$race_query = "SELECT raceid, racename FROM races";
$race_result = mysqli_query($connection, $race_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $raceid = $_POST['raceid'];
    $category = $_POST['category'];
    $section = $_POST['section'];
    $price = $_POST['price'];
    $totalseats = $_POST['totalseats'];
    $availableseats = $_POST['availableseats'];

    $check_race = "SELECT raceid FROM races WHERE raceid = ?";
    $stmt = $connection->prepare($check_race);
    $stmt->bind_param("i", $raceid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $query = "INSERT INTO tickets (raceid, category, section, price, totalseats, availableseats) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("issdii", $raceid, $category, $section, $price, $totalseats, $availableseats);

        if ($stmt->execute()) {
            header("Location: admin_manage_tickets.php");
            exit();
        } else {
            echo "เกิดข้อผิดพลาด: " . $stmt->error;
        }
    } else {
        echo "เกิดข้อผิดพลาด: raceid ไม่ถูกต้อง กรุณาเลือกจากตัวเลือกที่มีอยู่";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Add Ticket</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>เพิ่มตั๋วใหม่</h2>
        <form method="POST">
            <div class="form-group">
                <label>เลือกเรซ (Race ID)</label>
                <select class="form-control" name="raceid" required>
                    <option value="">-- กรุณาเลือก --</option>
                    <?php while ($row = mysqli_fetch_assoc($race_result)) { ?>
                        <option value="<?= $row['raceid'] ?>"><?= $row['racename'] ?> (ID: <?= $row['raceid'] ?>)</option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>ประเภทตั๋ว</label>
                <input type="text" class="form-control" name="category" required>
            </div>
            <div class="form-group">
                <label>โซน</label>
                <input type="text" class="form-control" name="section" required>
            </div>
            <div class="form-group">
                <label>ราคา</label>
                <input type="number" class="form-control" name="price" required>
            </div>
            <div class="form-group">
                <label>จำนวนที่นั่งทั้งหมด</label>
                <input type="number" class="form-control" name="totalseats" required>
            </div>
            <div class="form-group">
                <label>จำนวนที่นั่งว่าง</label>
                <input type="number" class="form-control" name="availableseats" required>
            </div>
            <button type="submit" class="btn btn-success">เพิ่มตั๋ว</button>
            <a href="admin_manage_tickets.php" class="btn btn-secondary">กลับ</a>
        </form>
    </div>
</body>
</html>
