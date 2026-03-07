<?php
include 'db.php';
include 'components/admin_header.php';

$success = '';
$error = '';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>ไม่พบรหัสข่าว</div></div>";
    include 'components/admin_footer.php';
    exit();
}

$stmt = mysqli_prepare($connection, "SELECT * FROM news WHERE news_id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$news = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$news) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>ไม่พบข้อมูลข่าว</div></div>";
    include 'components/admin_footer.php';
    exit();
}

$tickets_query = "SELECT ticketid, category, section FROM tickets ORDER BY ticketid DESC";
$tickets_result = mysqli_query($connection, $tickets_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $summary = trim($_POST['summary'] ?? '');
    $image_url = trim($_POST['image_url'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $news_type = $_POST['news_type'] ?? 'general';
    $status = $_POST['status'] ?? 'draft';
    $ticket_id = !empty($_POST['ticket_id']) ? (int)$_POST['ticket_id'] : null;

    if ($title === '' || $content === '') {
        $error = 'กรุณากรอกหัวข้อข่าวและเนื้อหาให้ครบ';
    } else {
        $update_stmt = mysqli_prepare(
            $connection,
            "UPDATE news SET title = ?, summary = ?, image_url = ?, content = ?, news_type = ?, status = ?, ticket_id = ? WHERE news_id = ?"
        );
        mysqli_stmt_bind_param(
            $update_stmt,
            "ssssssii",
            $title,
            $summary,
            $image_url,
            $content,
            $news_type,
            $status,
            $ticket_id,
            $id
        );

        if (mysqli_stmt_execute($update_stmt)) {
            $success = 'แก้ไขข่าวเรียบร้อยแล้ว';

            $news['title'] = $title;
            $news['summary'] = $summary;
            $news['image_url'] = $image_url;
            $news['content'] = $content;
            $news['news_type'] = $news_type;
            $news['status'] = $status;
            $news['ticket_id'] = $ticket_id;
        } else {
            $error = 'เกิดข้อผิดพลาดในการแก้ไขข่าว';
        }

        mysqli_stmt_close($update_stmt);
    }
}
?>

<div class="container mt-4 mb-5">
    <h2 class="text-center mb-4">แก้ไขข่าว</h2>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="table-card">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label>หัวข้อข่าว</label>
                        <input type="text" name="title" class="form-control"
                               value="<?php echo htmlspecialchars($news['title']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>สรุปข่าว / คำโปรย</label>
                        <textarea name="summary" class="form-control" rows="3"><?php echo htmlspecialchars($news['summary'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>ลิงก์รูปภาพข่าว</label>
                        <input type="text" name="image_url" class="form-control"
                               value="<?php echo htmlspecialchars($news['image_url'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>เนื้อข่าว</label>
                        <textarea name="content" class="form-control" rows="10" required><?php echo htmlspecialchars($news['content']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>ประเภทข่าว</label>
                        <select name="news_type" id="news_type" class="form-control">
                            <option value="general" <?php echo ($news['news_type'] === 'general') ? 'selected' : ''; ?>>ทั่วไป</option>
                            <option value="ticket_sale" <?php echo ($news['news_type'] === 'ticket_sale') ? 'selected' : ''; ?>>เปิดขายตั๋ว</option>
                        </select>
                    </div>

                    <div class="form-group" id="ticket_box" style="<?php echo ($news['news_type'] === 'ticket_sale') ? '' : 'display: none;'; ?>">
                        <label>เลือกตั๋วที่เกี่ยวข้อง</label>
                        <select name="ticket_id" class="form-control">
                            <option value="">-- เลือกตั๋ว --</option>
                            <?php if ($tickets_result && mysqli_num_rows($tickets_result) > 0): ?>
                                <?php while ($ticket = mysqli_fetch_assoc($tickets_result)): ?>
                                    <option value="<?php echo $ticket['ticketid']; ?>"
                                        <?php echo ((int)($news['ticket_id'] ?? 0) === (int)$ticket['ticketid']) ? 'selected' : ''; ?>>
                                        Ticket #<?php echo htmlspecialchars($ticket['ticketid']); ?>
                                        - <?php echo htmlspecialchars($ticket['category']); ?>
                                        - <?php echo htmlspecialchars($ticket['section']); ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>สถานะ</label>
                        <select name="status" class="form-control">
                            <option value="draft" <?php echo ($news['status'] === 'draft') ? 'selected' : ''; ?>>Draft</option>
                            <option value="publish" <?php echo ($news['status'] === 'publish') ? 'selected' : ''; ?>>Publish</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="admin_news.php" class="btn btn-secondary">กลับ</a>
                        <button type="submit" class="btn btn-warning">อัปเดตข่าว</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('news_type').addEventListener('change', function () {
    const ticketBox = document.getElementById('ticket_box');
    ticketBox.style.display = this.value === 'ticket_sale' ? 'block' : 'none';
});
</script>

<?php include 'components/admin_footer.php'; ?>