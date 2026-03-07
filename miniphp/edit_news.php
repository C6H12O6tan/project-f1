<?php
include 'db.php';

$current_page = 'admin_news.php';
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

$old_status = $news['status'] ?? 'draft';

$tickets_query = "SELECT ticketid, category, section FROM tickets ORDER BY ticketid DESC";
$tickets_result = mysqli_query($connection, $tickets_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $summary = trim($_POST['summary'] ?? '');
    $image_url = trim($_POST['image_url'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $news_type = $_POST['news_type'] ?? 'general';
    $status = $_POST['status'] ?? 'draft';
    $ticket_id = $_POST['ticket_id'] ?? '';

    if ($news_type !== 'ticket_sale') {
        $ticket_id = '';
    }

    if ($title === '' || $content === '') {
        $error = 'กรุณากรอกหัวข้อข่าวและเนื้อหาให้ครบ';
    } else {
        if ($ticket_id !== '') {
            $ticket_id_int = (int)$ticket_id;

            $update_stmt = mysqli_prepare(
                $connection,
                "UPDATE news
                 SET title = ?, summary = ?, image_url = ?, content = ?, news_type = ?, status = ?, ticket_id = ?
                 WHERE news_id = ?"
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
                $ticket_id_int,
                $id
            );
        } else {
            $update_stmt = mysqli_prepare(
                $connection,
                "UPDATE news
                 SET title = ?, summary = ?, image_url = ?, content = ?, news_type = ?, status = ?, ticket_id = NULL
                 WHERE news_id = ?"
            );

            mysqli_stmt_bind_param(
                $update_stmt,
                "ssssssi",
                $title,
                $summary,
                $image_url,
                $content,
                $news_type,
                $status,
                $id
            );
        }

        if ($update_stmt && mysqli_stmt_execute($update_stmt)) {
            $just_published = ($old_status !== 'publish' && $status === 'publish');

            if ($just_published) {
                $link = "news_detail.php?id=" . $id;
                $message = ($news_type === 'ticket_sale')
                    ? 'มีข่าวเปิดขายตั๋วใหม่'
                    : 'มีข่าวใหม่ในระบบ';

                $users_stmt = mysqli_prepare($connection, "SELECT user_id FROM users WHERE user_type != 'admin'");

                if ($users_stmt) {
                    mysqli_stmt_execute($users_stmt);
                    $users_result = mysqli_stmt_get_result($users_stmt);

                    while ($user = mysqli_fetch_assoc($users_result)) {
                        $user_id = (int)$user['user_id'];

                        $noti_stmt = mysqli_prepare(
                            $connection,
                            "INSERT INTO notifications (user_id, title, message, link) VALUES (?, ?, ?, ?)"
                        );

                        if ($noti_stmt) {
                            mysqli_stmt_bind_param($noti_stmt, "isss", $user_id, $title, $message, $link);
                            mysqli_stmt_execute($noti_stmt);
                            mysqli_stmt_close($noti_stmt);
                        }
                    }

                    mysqli_stmt_close($users_stmt);
                }
            }

            $success = 'แก้ไขข่าวเรียบร้อยแล้ว';

            $news['title'] = $title;
            $news['summary'] = $summary;
            $news['image_url'] = $image_url;
            $news['content'] = $content;
            $news['news_type'] = $news_type;
            $news['status'] = $status;
            $news['ticket_id'] = ($ticket_id !== '') ? (int)$ticket_id : null;

            $old_status = $status;
        } else {
            $error = 'เกิดข้อผิดพลาดในการแก้ไขข่าว';
        }

        if ($update_stmt) {
            mysqli_stmt_close($update_stmt);
        }
    }
}
?>

<div class="container mt-4 mb-5">
    <header class="custom-header text-center py-4">
        <h1>Edit News</h1>
        <p>อัปเดตข้อมูลข่าวที่จะใช้แสดงผลในฝั่งผู้ใช้</p>
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
                        <label>หัวข้อข่าว</label>
                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            value="<?php echo htmlspecialchars($news['title']); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>สรุปข่าว / คำโปรย</label>
                        <textarea
                            name="summary"
                            class="form-control"
                            rows="3"
                            placeholder="ข้อความสั้น ๆ ใต้หัวข้อข่าว"
                        ><?php echo htmlspecialchars($news['summary'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>ลิงก์รูปภาพข่าว</label>
                        <input
                            type="text"
                            name="image_url"
                            class="form-control"
                            value="<?php echo htmlspecialchars($news['image_url'] ?? ''); ?>"
                            placeholder="https://example.com/image.jpg"
                        >
                    </div>

                    <div class="form-group">
                        <label>เนื้อข่าว</label>
                        <textarea
                            name="content"
                            class="form-control"
                            rows="10"
                            required
                            placeholder="พิมพ์เนื้อหาข่าวที่นี่"
                        ><?php echo htmlspecialchars($news['content']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>ประเภทข่าว</label>
                        <select name="news_type" id="news_type" class="form-control">
                            <option value="general" <?php echo ($news['news_type'] === 'general') ? 'selected' : ''; ?>>
                                ทั่วไป
                            </option>
                            <option value="ticket_sale" <?php echo ($news['news_type'] === 'ticket_sale') ? 'selected' : ''; ?>>
                                เปิดขายตั๋ว
                            </option>
                        </select>
                    </div>

                    <div
                        class="form-group"
                        id="ticket_box"
                        style="<?php echo ($news['news_type'] === 'ticket_sale') ? 'display:block;' : 'display:none;'; ?>"
                    >
                        <label>เลือกตั๋วที่เกี่ยวข้อง</label>
                        <select name="ticket_id" class="form-control">
                            <option value="">-- เลือกตั๋ว --</option>
                            <?php if ($tickets_result && mysqli_num_rows($tickets_result) > 0): ?>
                                <?php while ($ticket = mysqli_fetch_assoc($tickets_result)): ?>
                                    <option
                                        value="<?php echo htmlspecialchars($ticket['ticketid']); ?>"
                                        <?php echo ((int)($news['ticket_id'] ?? 0) === (int)$ticket['ticketid']) ? 'selected' : ''; ?>
                                    >
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
                            <option value="draft" <?php echo ($news['status'] === 'draft') ? 'selected' : ''; ?>>
                                Draft
                            </option>
                            <option value="publish" <?php echo ($news['status'] === 'publish') ? 'selected' : ''; ?>>
                                Publish
                            </option>
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