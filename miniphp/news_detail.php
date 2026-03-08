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

$publishedDate = !empty($news['created_at']) ? date('F j, Y', strtotime($news['created_at'])) : '-';
$newsTypeLabel = ($news['news_type'] === 'ticket_sale') ? 'TICKET SALE' : 'F1 NEWS';
?>

<div class="news-article-page">
    <div class="news-article-container">

        <a href="news.php" class="news-back-link">← Back to News</a>

        <div class="news-article-tag">
            <?php echo htmlspecialchars($newsTypeLabel); ?>
        </div>

        <div class="news-article-meta">
            Published: <?php echo htmlspecialchars($publishedDate); ?>
        </div>

        <h1 class="news-article-title">
            <?php echo htmlspecialchars($news['title']); ?>
        </h1>

        <?php if (!empty($news['summary'])): ?>
            <div class="news-article-summary">
                <?php echo nl2br(htmlspecialchars($news['summary'])); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($news['image_url'])): ?>
            <div class="news-article-image-wrap">
                <img
                    src="<?php echo htmlspecialchars($news['image_url']); ?>"
                    class="news-article-image"
                    alt="<?php echo htmlspecialchars($news['title']); ?>"
                >
            </div>
        <?php endif; ?>

        <div class="news-article-content">
            <?php
            $content = isset($news['content']) ? trim($news['content']) : '';

            if ($content !== '') {
                $paragraphs = preg_split("/\r\n\r\n|\n\n|\r\r/", $content);

                foreach ($paragraphs as $p) {
                    $p = trim($p);
                    if ($p !== '') {
                        echo "<p>" . nl2br(htmlspecialchars($p)) . "</p>";
                    }
                }
            } else {
                echo "<p>ไม่มีรายละเอียดเพิ่มเติม</p>";
            }
            ?>
        </div>

        <?php if ($news['news_type'] === 'ticket_sale' && !empty($news['ticket_id'])): ?>
            <div class="news-ticket-action">
                <a
                    href="book.php?ticketid=<?php echo (int)$news['ticket_id']; ?>"
                    class="news-ticket-button"
                >
                    ไปหน้าจอง / ซื้อตั๋ว
                </a>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php include 'components/footer.php'; ?>