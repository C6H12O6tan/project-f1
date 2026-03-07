<?php
include 'db.php';

$current_page = 'admin_highlights.php';
include 'components/admin_header.php';

$success = '';
$error = '';

$allowed_categories = ['Qualifying', 'Race', 'Pit', 'Overtake'];

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>ไม่พบรหัสไฮไลท์</div></div>";
    include 'components/admin_footer.php';
    exit();
}

$stmt = mysqli_prepare($connection, "SELECT * FROM highlights WHERE highlight_id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$highlight = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$highlight) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>ไม่พบข้อมูลไฮไลท์</div></div>";
    include 'components/admin_footer.php';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $youtube_url = trim($_POST['youtube_url'] ?? '');
    $category = trim($_POST['category'] ?? 'Race');

    if ($title === '' || $youtube_url === '') {
        $error = 'กรุณากรอกหัวข้อและลิงก์ YouTube ให้ครบ';
    } elseif (!in_array($category, $allowed_categories, true)) {
        $error = 'หมวดหมู่ไม่ถูกต้อง';
    } elseif (!filter_var($youtube_url, FILTER_VALIDATE_URL)) {
        $error = 'กรุณากรอกลิงก์ YouTube ให้ถูกต้อง';
    } else {
        $update_stmt = mysqli_prepare(
            $connection,
            "UPDATE highlights SET title = ?, youtube_url = ?, category = ? WHERE highlight_id = ?"
        );

        if ($update_stmt) {
            mysqli_stmt_bind_param($update_stmt, "sssi", $title, $youtube_url, $category, $id);

            if (mysqli_stmt_execute($update_stmt)) {
                $success = 'แก้ไขไฮไลท์เรียบร้อยแล้ว';
                $highlight['title'] = $title;
                $highlight['youtube_url'] = $youtube_url;
                $highlight['category'] = $category;
            } else {
                $error = 'เกิดข้อผิดพลาดในการแก้ไขไฮไลท์';
            }

            mysqli_stmt_close($update_stmt);
        } else {
            $error = 'ไม่สามารถเตรียมคำสั่ง SQL ได้';
        }
    }
}
?>

<div class="container mt-4 mb-5">
    <header class="custom-header text-center py-4">
        <h1>Edit Highlight</h1>
        <p>แก้ไขข้อมูลไฮไลท์การแข่งขัน</p>
    </header>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="table-card p-4">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label>หัวข้อไฮไลท์</label>
                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            required
                            value="<?php echo htmlspecialchars($highlight['title']); ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label>ลิงก์ YouTube</label>
                        <input
                            type="url"
                            name="youtube_url"
                            class="form-control"
                            required
                            value="<?php echo htmlspecialchars($highlight['youtube_url']); ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label>หมวดหมู่</label>
                        <select name="category" class="form-control">
                            <?php foreach ($allowed_categories as $item): ?>
                                <option value="<?php echo htmlspecialchars($item); ?>" <?php echo ($highlight['category'] === $item) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($item); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="admin_highlights.php" class="btn btn-secondary">กลับ</a>
                        <button type="submit" class="btn btn-warning">อัปเดตไฮไลท์</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'components/admin_footer.php'; ?>