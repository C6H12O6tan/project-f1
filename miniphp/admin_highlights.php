<?php
include 'db.php';

$current_page = 'admin_highlights.php';
include 'components/admin_header.php';

$query = "SELECT * FROM highlights ORDER BY created_at DESC, highlight_id DESC";
$result = mysqli_query($connection, $query);
?>

<header class="custom-header text-center py-4">
    <h1>Highlights Management</h1>
    <p>จัดการไฮไลท์การแข่งขันและลิงก์วิดีโอ YouTube</p>
</header>

<div class="container mt-5 mb-5">
    <div class="table-card">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <h4 class="mb-2">รายการไฮไลท์ทั้งหมด</h4>
            <a href="add_highlight.php" class="btn btn-primary mb-2">+ เพิ่มไฮไลท์</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>หัวข้อ</th>
                        <th>หมวดหมู่</th>
                        <th>YouTube Link</th>
                        <th>วันที่สร้าง</th>
                        <th width="180">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['highlight_id']); ?></td>

                                <td style="min-width: 240px;">
                                    <strong><?php echo htmlspecialchars($row['title']); ?></strong>
                                </td>

                                <td>
                                    <span class="badge badge-info">
                                        <?php echo htmlspecialchars($row['category']); ?>
                                    </span>
                                </td>

                                <td style="min-width: 260px;">
                                    <?php if (!empty($row['youtube_url'])): ?>
                                        <a href="<?php echo htmlspecialchars($row['youtube_url']); ?>" target="_blank" rel="noopener noreferrer">
                                            <?php echo htmlspecialchars($row['youtube_url']); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php echo !empty($row['created_at']) ? htmlspecialchars($row['created_at']) : '-'; ?>
                                </td>

                                <td>
                                    <a href="edit_highlight.php?id=<?php echo urlencode($row['highlight_id']); ?>" class="btn btn-warning btn-sm mb-1">
                                        Edit
                                    </a>
                                    <a href="delete_highlight.php?id=<?php echo urlencode($row['highlight_id']); ?>"
                                       class="btn btn-danger btn-sm mb-1"
                                       onclick="return confirm('ยืนยันการลบไฮไลท์นี้หรือไม่?');">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">ยังไม่มีข้อมูลไฮไลท์</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'components/admin_footer.php'; ?>