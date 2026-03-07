<?php
include 'db.php';
include 'components/admin_header.php';

$query = "
    SELECT news.*, tickets.category AS ticket_category, tickets.section AS ticket_section
    FROM news
    LEFT JOIN tickets ON news.ticket_id = tickets.ticketid
    ORDER BY news.created_at DESC
";
$result = mysqli_query($connection, $query);
?>

<div class="container mt-5 mb-5">
    <h2 class="text-center mb-4">จัดการข่าว</h2>
    <p class="text-center text-muted mb-4">เพิ่ม แก้ไข และเผยแพร่ข่าวสำหรับผู้ใช้</p>

    <div class="table-card">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <h4 class="mb-2">รายการข่าวทั้งหมด</h4>
            <a href="add_news.php" class="btn btn-primary mb-2">+ เพิ่มข่าว</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>รูป</th>
                        <th>หัวข้อข่าว</th>
                        <th>ประเภท</th>
                        <th>สถานะ</th>
                        <th>ตั๋วที่เชื่อม</th>
                        <th>วันที่สร้าง</th>
                        <th width="180">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['news_id']); ?></td>
                                <td>
                                    <?php if (!empty($row['image_url'])): ?>
                                        <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="thumb" style="width:80px;height:50px;object-fit:cover;border-radius:6px;">
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['title']); ?></strong>
                                    <?php if (!empty($row['summary'])): ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($row['summary']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($row['news_type'] === 'ticket_sale'): ?>
                                        <span class="badge badge-success">เปิดขายตั๋ว</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">ทั่วไป</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($row['status'] === 'publish'): ?>
                                        <span class="badge badge-primary">Publish</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    if (!empty($row['ticket_id'])) {
                                        echo 'Ticket #' . htmlspecialchars($row['ticket_id']);
                                        if (!empty($row['ticket_category']) || !empty($row['ticket_section'])) {
                                            echo '<br><small>' . htmlspecialchars($row['ticket_category'] ?? '') . ' ' . htmlspecialchars($row['ticket_section'] ?? '') . '</small>';
                                        }
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                <td>
                                    <a href="edit_news.php?id=<?php echo $row['news_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete_news.php?id=<?php echo $row['news_id']; ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('ยืนยันการลบข่าวนี้หรือไม่?');">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">ยังไม่มีข้อมูลข่าว</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'components/admin_footer.php'; ?>