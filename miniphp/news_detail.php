<?php
include 'db.php';
include 'components/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>ไม่พบข่าว</div></div>";
    include 'components/footer.php';
    exit();
}

$stmt = mysqli_prepare(
    $connection,
    "SELECT * FROM news WHERE news_id = ? AND status = 'publish'"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$news = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$news) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>ไม่พบข่าวหรือข่าวยังไม่ถูกเผยแพร่</div></div>";
    include 'components/footer.php';
    exit();
}
?>

<div class="container article-page">
    <div class="article-box">
        <a href="news.php" class="article-back">← กลับหน้าข่าว</a>

        <div class="article-tag">
            <?php echo ($news['news_type'] === 'ticket_sale') ? 'เปิดขายตั๋ว' : 'ข่าวทั่วไป'; ?>
        </div>

        <div class="article-meta">
            วันที่เผยแพร่: <?php echo htmlspecialchars($news['created_at']); ?>
        </div>

        <h1 class="article-title">
            <?php echo htmlspecialchars($news['title']); ?>
        </h1>

        <?php if (!empty($news['summary'])): ?>
            <div class="article-summary">
                <?php echo nl2br(htmlspecialchars($news['summary'])); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($news['image_url'])): ?>
            <img
                src="<?php echo htmlspecialchars($news['image_url']); ?>"
                class="article-cover"
                alt="news cover"
            >
        <?php endif; ?>

        <div class="article-content">
            <?php
            $paragraphs = preg_split("/\r\n\r\n|\n\n|\r\r/", trim($news['content']));
            foreach ($paragraphs as $p) {
                $p = trim($p);
                if ($p !== '') {
                    echo "<p>" . nl2br(htmlspecialchars($p)) . "</p>";
                }
            }
            ?>
        </div>

        <?php if ($news['news_type'] === 'ticket_sale' && !empty($news['ticket_id'])): ?>
            <a href="tickets.php?id=<?php echo urlencode($news['ticket_id']); ?>" class="ticket-button">
                ไปหน้าจอง / ซื้อตั๋ว
            </a>
        <?php endif; ?>
    </div>
</div>

<?php include 'components/footer.php'; ?>