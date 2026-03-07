<?php
include 'db.php';

$current_page = 'admin_highlights.php';
include 'components/admin_header.php';

$success = '';
$error = '';

$title = '';
$youtube_url = '';
$category = 'Race';

$allowed_categories = ['Qualifying', 'Race', 'Pit', 'Overtake'];

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
        $stmt = mysqli_prepare(
            $connection,
            "INSERT INTO highlights (title, youtube_url, category) VALUES (?, ?, ?)"
        );

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $title, $youtube_url, $category);

            if (mysqli_stmt_execute($stmt)) {
                $success = 'เพิ่มไฮไลท์เรียบร้อยแล้ว';
                $title = '';
                $youtube_url = '';
                $category = 'Race';
            } else {
                $error = 'เกิดข้อผิดพลาดในการเพิ่มไฮไลท์';
            }

            mysqli_stmt_close($stmt);
        } else {
            $error = 'ไม่สามารถเตรียมคำสั่ง SQL ได้';
        }
    }
}
?>

<div class="container mt-4 mb-5">
    <header class="custom-header text-center py-4">
        <h1>Add Highlight</h1>
        <p>เพิ่มไฮไลท์การแข่งขันด้วยลิงก์วิดีโอ YouTube</p>
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
                            value="<?php echo htmlspecialchars($title); ?>"
                            placeholder="เช่น Australian GP Race Highlights"
                        >
                    </div>

                    <div class="form-group">
                        <label>ลิงก์ YouTube</label>
                        <input
                            type="url"
                            name="youtube_url"
                            class="form-control"
                            required
                            value="<?php echo htmlspecialchars($youtube_url); ?>"
                            placeholder="https://www.youtube.com/watch?v=..."
                        >
                    </div>

                    <div class="form-group">
                        <label>หมวดหมู่</label>
                        <select name="category" class="form-control">
                            <?php foreach ($allowed_categories as $item): ?>
                                <option value="<?php echo htmlspecialchars($item); ?>" <?php echo ($category === $item) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($item); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="admin_highlights.php" class="btn btn-secondary">กลับ</a>
                        <button type="submit" class="btn btn-primary">บันทึกไฮไลท์</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'components/admin_footer.php'; ?>