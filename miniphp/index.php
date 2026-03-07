<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

session_regenerate_id(true);

include 'db.php';
include 'components/header.php';

/**
 * ดึงข่าวล่าสุด 3 ข่าวที่ publish
 */
$latestNews = [];
$newsQuery = "SELECT news_id, title, summary, image_url, created_at, news_type, ticket_id
              FROM news
              WHERE status = 'publish'
              ORDER BY created_at DESC
              LIMIT 3";
$newsResult = mysqli_query($connection, $newsQuery);

if ($newsResult && mysqli_num_rows($newsResult) > 0) {
    while ($row = mysqli_fetch_assoc($newsResult)) {
        $latestNews[] = $row;
    }
}

/**
 * รูป hero
 */
$heroImage = 'https://images.unsplash.com/photo-1504707748692-419802cf939d?auto=format&fit=crop&w=1200&q=80';

if (!empty($latestNews) && !empty($latestNews[0]['image_url'])) {
    $heroImage = $latestNews[0]['image_url'];
}
?>

<div class="home-user-page">

    <section class="home-hero">
        <div class="container">
            <div class="home-hero-grid">
                <div class="home-hero-content">
                    <h1 class="home-hero-title">
                        Book Your Formula <br>1 Experience
                    </h1>

                    <p class="home-hero-subtitle">
                        Users can book tickets, read latest news, and manage reservations.
                    </p>

                    <div class="home-hero-actions">
                        <a href="tickets.php" class="home-btn-primary">Book Tickets →</a>
                        <a href="news.php" class="home-btn-secondary">Latest News</a>
                    </div>
                </div>

                <div class="home-hero-media">
                    <img
                        src="<?php echo htmlspecialchars($heroImage); ?>"
                        alt="Formula 1 Hero"
                        class="home-hero-image"
                    >
                </div>
            </div>
        </div>
    </section>

    <section class="home-features-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="home-feature-card">
                        <div class="home-feature-icon">🎟️</div>
                        <h3 class="home-feature-title">Tickets</h3>
                        <p class="home-feature-text">
                            Browse and book tickets for upcoming Formula 1 races.
                        </p>
                        <a href="tickets.php" class="home-feature-link">Learn more →</a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="home-feature-card">
                        <div class="home-feature-icon">📰</div>
                        <h3 class="home-feature-title">News</h3>
                        <p class="home-feature-text">
                            Stay updated with the latest Formula 1 news and race results.
                        </p>
                        <a href="news.php" class="home-feature-link">Learn more →</a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="home-feature-card">
                        <div class="home-feature-icon">📅</div>
                        <h3 class="home-feature-title">Reservations</h3>
                        <p class="home-feature-text">
                            View and manage your ticket reservations.
                        </p>
                        <a href="bookings.php" class="home-feature-link">Learn more →</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home-news-section">
        <div class="container">
            <div class="home-section-head">
                <h2 class="home-section-title">Latest News</h2>
                <a href="news.php" class="home-section-link">View all news</a>
            </div>

            <div class="row g-4">
                <?php if (!empty($latestNews)): ?>
                    <?php foreach ($latestNews as $news): ?>
                        <?php
                        $dateText = !empty($news['created_at']) ? date('F j, Y', strtotime($news['created_at'])) : '-';
                        $summaryText = !empty($news['summary']) ? $news['summary'] : 'Read the latest update from the Formula 1 world.';
                        $newsImage = !empty($news['image_url'])
                            ? $news['image_url']
                            : 'https://images.unsplash.com/photo-1541773367336-d14b1f7b05d1?auto=format&fit=crop&w=900&q=80';
                        ?>
                        <div class="col-lg-4 col-md-6">
                            <article class="home-news-card">
                                <a href="news_detail.php?id=<?php echo urlencode($news['news_id']); ?>" class="home-news-image-link">
                                    <img
                                        src="<?php echo htmlspecialchars($newsImage); ?>"
                                        alt="<?php echo htmlspecialchars($news['title']); ?>"
                                        class="home-news-image"
                                    >
                                </a>

                                <div class="home-news-body">
                                    <div class="home-news-date">
                                        <?php echo htmlspecialchars($dateText); ?>
                                    </div>

                                    <h3 class="home-news-title">
                                        <a href="news_detail.php?id=<?php echo urlencode($news['news_id']); ?>" class="home-news-title-link">
                                            <?php echo htmlspecialchars($news['title']); ?>
                                        </a>
                                    </h3>

                                    <p class="home-news-summary">
                                        <?php echo htmlspecialchars(mb_strimwidth(strip_tags($summaryText), 0, 110, '...')); ?>
                                    </p>

                                    <a href="news_detail.php?id=<?php echo urlencode($news['news_id']); ?>" class="home-news-readmore">
                                        Read More →
                                    </a>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="home-empty-news">
                            ยังไม่มีข่าวที่เผยแพร่ในระบบตอนนี้
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

</div>

<?php include 'components/footer.php'; ?>