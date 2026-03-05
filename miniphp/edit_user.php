<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['user_id'])) {
    echo "ไม่พบรหัสผู้ใช้!";
    exit();
}

$user_id = $_GET['user_id'];
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows <= 0) {
    echo "ไม่พบข้อมูลผู้ใช้!";
    exit();
}

$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $user_type = $_POST['user_type'];

    if (!empty($_POST['password'])) {
        $password = $_POST['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_query = "UPDATE users 
                         SET first_name=?, last_name=?, email=?, password=?, phone_number=?, user_type=? 
                         WHERE user_id=?";
        $stmt = $connection->prepare($update_query);
        $stmt->bind_param("ssssssi", $first_name, $last_name, $email, $hashed_password, $phone_number, $user_type, $user_id);
    } else {
        $update_query = "UPDATE users 
                         SET first_name=?, last_name=?, email=?, phone_number=?, user_type=? 
                         WHERE user_id=?";
        $stmt = $connection->prepare($update_query);
        $stmt->bind_param("sssssi", $first_name, $last_name, $email, $phone_number, $user_type, $user_id);
    }

    if ($stmt->execute()) {
        header("Location: admin_manage_users.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขผู้ใช้</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>แก้ไขผู้ใช้</h2>
    <form method="POST">
        <div class="form-group">
            <label>ชื่อ</label>
            <input type="text" name="first_name" class="form-control" value="<?php echo $user['first_name']; ?>" required>
        </div>
        <div class="form-group">
            <label>นามสกุล</label>
            <input type="text" name="last_name" class="form-control" value="<?php echo $user['last_name']; ?>" required>
        </div>
        <div class="form-group">
            <label>อีเมล</label>
            <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
        </div>
        <div class="form-group">
            <label>รหัสผ่าน (เว้นว่างหากไม่ต้องการเปลี่ยน)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="form-group">
            <label>เบอร์โทร</label>
            <input type="text" name="phone_number" class="form-control" value="<?php echo $user['phone_number']; ?>" required>
        </div>
        <div class="form-group">
            <label>ประเภทผู้ใช้</label>
            <select name="user_type" class="form-control">
                <option value="user" <?php echo ($user['user_type'] == 'user') ? 'selected' : ''; ?>>user</option>
                <option value="admin" <?php echo ($user['user_type'] == 'admin') ? 'selected' : ''; ?>>admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
        <a href="admin_manage_users.php" class="btn btn-secondary">กลับ</a>
    </form>
</div>
</body>
</html>
