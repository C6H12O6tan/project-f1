<?php
include 'db.php';
include 'components/header.php';

$query = "SELECT * FROM news WHERE status = 'publish' ORDER BY created_at DESC";
$result = mysqli_query($connection, $query);
?>

<div class="container news-page">
    <h1 class="news-page-title">ข่าวสารล่าสุด</h1>

    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="col-md-6">
                    <div class="news-card">
                        <?php if (!empty($row['image_url'])): ?>
                            <img
                                src="<?php echo htmlspecialchars($row['image_url']); ?>"
                                class="news-card-image"
                                alt="news image"
                            >
                        <?php endif; ?>

                        <div class="news-card-body">
                            <div class="news-card-meta">
                                <?php echo htmlspecialchars($row['created_at']); ?> |
                                <?php echo ($row['news_type'] === 'ticket_sale') ? 'เปิดขายตั๋ว' : 'ข่าวทั่วไป'; ?>
                            </div>

                            <div class="news-card-title">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </div>

                            <?php if (!empty($row['summary'])): ?>
                                <div class="news-card-summary">
                                    <?php echo nl2br(htmlspecialchars($row['summary'])); ?>
                                </div>
                            <?php endif; ?>

                            <a
                                href="news_detail.php?id=<?php echo $row['news_id']; ?>"
                                class="news-read-more"
                            >
                                อ่านต่อ
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php else: ?>
        <div class="alert alert-secondary text-center">ยังไม่มีข่าวในระบบ</div>
    <?php endif; ?>
</div>

<?php include 'components/footer.php'; ?>