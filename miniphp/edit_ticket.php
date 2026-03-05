<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$ticketid = $_GET['ticketid'];
$query = "SELECT * FROM tickets WHERE ticketid = '$ticketid'";
$result = mysqli_query($connection, $query);
$ticket = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];
    $section = $_POST['section'];
    $price = $_POST['price'];
    $totalseats = $_POST['totalseats'];
    $availableseats = $_POST['availableseats'];

    $update_query = "UPDATE tickets SET 
                     category='$category', section='$section', price='$price', 
                     totalseats='$totalseats', availableseats='$availableseats' 
                     WHERE ticketid='$ticketid'";

    if (mysqli_query($connection, $update_query)) {
        header("Location: admin_manage_tickets.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Edit Ticket</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>แก้ไขตั๋ว</h2>
        <form method="POST">
            <div class="form-group">
                <label>ประเภทตั๋ว</label>
                <input type="text" class="form-control" name="category" value="<?php echo $ticket['category']; ?>" required>
            </div>
            <div class="form-group">
                <label>โซน</label>
                <input type="text" class="form-control" name="section" value="<?php echo $ticket['section']; ?>" required>
            </div>
            <div class="form-group">
                <label>ราคา</label>
                <input type="number" class="form-control" name="price" value="<?php echo $ticket['price']; ?>" required>
            </div>
            <div class="form-group">
                <label>จำนวนที่นั่งทั้งหมด</label>
                <input type="number" class="form-control" name="totalseats" value="<?php echo $ticket['totalseats']; ?>" required>
            </div>
            <div class="form-group">
                <label>จำนวนที่นั่งว่าง</label>
                <input type="number" class="form-control" name="availableseats" value="<?php echo $ticket['availableseats']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
        </form>
    </div>
</body>
</html>
