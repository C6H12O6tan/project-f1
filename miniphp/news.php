<?php
include 'db.php';
include 'components/header.php';

$query = "SELECT * FROM news WHERE status = 'publish' ORDER BY created_at DESC";
$result = mysqli_query($connection, $query);
?>

<div class="container news-page">
    <div class="news-page-header">
        <div class="news-page-kicker">F1 NEWS</div>
        <h1 class="news-page-title">ข่าวสารล่าสุด</h1>
        <p class="news-page-subtitle">อัปเดตข่าว Formula 1, ข่าวเปิดขายตั๋ว และข้อมูลสำคัญล่าสุดสำหรับผู้ใช้งาน</p>
    </div>

    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <?php
                $typeLabel = ($row['news_type'] === 'ticket_sale') ? 'เปิดขายตั๋ว' : 'ข่าวทั่วไป';
                $dateText = !empty($row['created_at']) ? date('F j, Y', strtotime($row['created_at'])) : '-';
                ?>
                <div class="col-lg-6 col-md-6">
                    <article class="news-card">
                        <?php if (!empty($row['image_url'])): ?>
                            <a href="news_detail.php?id=<?php echo urlencode($row['news_id']); ?>" class="news-card-image-link">
                                <img
                                    src="<?php echo htmlspecialchars($row['image_url']); ?>"
                                    class="news-card-image"
                                    alt="news image"
                                >
                            </a>
                        <?php endif; ?>

                        <div class="news-card-body">
                            <div class="news-card-topline">
                                <span class="news-card-type <?php echo ($row['news_type'] === 'ticket_sale') ? 'is-ticket' : 'is-general'; ?>">
                                    <?php echo htmlspecialchars($typeLabel); ?>
                                </span>
                                <span class="news-card-meta">
                                    <?php echo htmlspecialchars($dateText); ?>
                                </span>
                            </div>

                            <h2 class="news-card-title">
                                <a href="news_detail.php?id=<?php echo urlencode($row['news_id']); ?>" class="news-card-title-link">
                                    <?php echo htmlspecialchars($row['title']); ?>
                                </a>
                            </h2>

                            <?php if (!empty($row['summary'])): ?>
                                <div class="news-card-summary">
                                    <?php echo nl2br(htmlspecialchars($row['summary'])); ?>
                                </div>
                            <?php endif; ?>

                            <a
                                href="news_detail.php?id=<?php echo urlencode($row['news_id']); ?>"
                                class="news-read-more"
                            >
                                อ่านต่อ
                            </a>
                        </div>
                    </article>
                </div>
            <?php } ?>
        </div>
    <?php else: ?>
        <div class="alert alert-secondary text-center">ยังไม่มีข่าวในระบบ</div>
    <?php endif; ?>
</div>

<?php include 'components/footer.php'; ?>