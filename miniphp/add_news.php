<?php
include 'db.php';
include 'components/admin_header.php';

$success = '';
$error = '';

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
        $stmt = mysqli_prepare(
            $connection,
            "INSERT INTO news (title, summary, image_url, content, news_type, status, ticket_id) VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssssssi",
            $title,
            $summary,
            $image_url,
            $content,
            $news_type,
            $status,
            $ticket_id
        );

        if (mysqli_stmt_execute($stmt)) {
            $news_id = mysqli_insert_id($connection);

            if ($status === 'publish') {
                $link = "news_detail.php?id=" . $news_id;
                $message = ($news_type === 'ticket_sale')
                    ? 'มีข่าวเปิดขายตั๋วใหม่'
                    : 'มีข่าวใหม่ในระบบ';

                $users_query = "SELECT user_id FROM users WHERE user_type != 'admin'";
                $users_result = mysqli_query($connection, $users_query);

                if ($users_result) {
                    while ($user = mysqli_fetch_assoc($users_result)) {
                        $user_id = (int)$user['user_id'];

                        $noti_stmt = mysqli_prepare(
                            $connection,
                            "INSERT INTO notifications (user_id, title, message, link) VALUES (?, ?, ?, ?)"
                        );
                        mysqli_stmt_bind_param($noti_stmt, "isss", $user_id, $title, $message, $link);
                        mysqli_stmt_execute($noti_stmt);
                        mysqli_stmt_close($noti_stmt);
                    }
                }
            }

            $success = 'เพิ่มข่าวเรียบร้อยแล้ว';
        } else {
            $error = 'เกิดข้อผิดพลาดในการเพิ่มข่าว';
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<div class="container mt-4 mb-5">
    <h2 class="text-center mb-4">เพิ่มข่าว</h2>

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
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>สรุปข่าว / คำโปรย</label>
                        <textarea name="summary" class="form-control" rows="3" placeholder="ข้อความสั้น ๆ ใต้หัวข้อข่าว"></textarea>
                    </div>

                    <div class="form-group">
                        <label>ลิงก์รูปภาพข่าว</label>
                        <input type="text" name="image_url" class="form-control" placeholder="https://example.com/image.jpg">
                    </div>

                    <div class="form-group">
                        <label>เนื้อข่าว</label>
                        <textarea name="content" class="form-control" rows="10" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>ประเภทข่าว</label>
                        <select name="news_type" id="news_type" class="form-control">
                            <option value="general">ทั่วไป</option>
                            <option value="ticket_sale">เปิดขายตั๋ว</option>
                        </select>
                    </div>

                    <div class="form-group" id="ticket_box" style="display: none;">
                        <label>เลือกตั๋วที่เกี่ยวข้อง</label>
                        <select name="ticket_id" class="form-control">
                            <option value="">-- เลือกตั๋ว --</option>
                            <?php if ($tickets_result && mysqli_num_rows($tickets_result) > 0): ?>
                                <?php while ($ticket = mysqli_fetch_assoc($tickets_result)): ?>
                                    <option value="<?php echo $ticket['ticketid']; ?>">
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
                            <option value="draft">Draft</option>
                            <option value="publish">Publish</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="admin_news.php" class="btn btn-secondary">กลับ</a>
                        <button type="submit" class="btn btn-primary">บันทึกข่าว</button>
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