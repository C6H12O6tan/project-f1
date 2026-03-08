<?php
include 'components/header.php';

$category = isset($_GET['category']) ? trim($_GET['category']) : '';

$allowedCategories = ['Qualifying', 'Race', 'Pit', 'Overtake'];

if ($category !== '' && !in_array($category, $allowedCategories, true)) {
    $category = '';
}

if ($category !== '') {
    $sql = "SELECT * FROM highlights WHERE category = ? ORDER BY created_at DESC";
    $stmt = mysqli_prepare($connection, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $category);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $result = false;
    }
} else {
    $sql = "SELECT * FROM highlights ORDER BY created_at DESC";
    $result = mysqli_query($connection, $sql);
}
?>

<div class="highlights-page">
    <div class="container highlights-container">
        <div class="highlights-header">
            <h1 class="highlights-title">Race Highlights</h1>
            <p class="highlights-subtitle">
                Watch the latest Formula 1 qualifying, race, pit stop, and overtake highlights.
            </p>
        </div>

        <div class="highlights-filters">
            <a href="highlights.php" class="highlight-filter-btn <?php echo $category === '' ? 'active' : ''; ?>">
                All
            </a>
            <a href="highlights.php?category=Qualifying" class="highlight-filter-btn <?php echo $category === 'Qualifying' ? 'active' : ''; ?>">
                Qualifying
            </a>
            <a href="highlights.php?category=Race" class="highlight-filter-btn <?php echo $category === 'Race' ? 'active' : ''; ?>">
                Race
            </a>
            <a href="highlights.php?category=Pit" class="highlight-filter-btn <?php echo $category === 'Pit' ? 'active' : ''; ?>">
                Pit
            </a>
            <a href="highlights.php?category=Overtake" class="highlight-filter-btn <?php echo $category === 'Overtake' ? 'active' : ''; ?>">
                Overtake
            </a>
        </div>

        <div class="row g-4">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <?php
                    $videoUrl = trim($row['youtube_url'] ?? '');
                    $embedUrl = '';
                    $watchUrl = $videoUrl;

                    if ($videoUrl !== '') {
                        if (strpos($videoUrl, 'youtube.com/watch?v=') !== false) {
                            $parts = parse_url($videoUrl);
                            if (!empty($parts['query'])) {
                                parse_str($parts['query'], $queryParams);
                                if (!empty($queryParams['v'])) {
                                    $embedUrl = 'https://www.youtube.com/embed/' . $queryParams['v'];
                                }
                            }
                        } elseif (strpos($videoUrl, 'youtu.be/') !== false) {
                            $parts = parse_url($videoUrl);
                            if (!empty($parts['path'])) {
                                $videoId = trim($parts['path'], '/');
                                if ($videoId !== '') {
                                    $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                                }
                            }
                        } elseif (strpos($videoUrl, 'youtube.com/embed/') !== false) {
                            $embedUrl = $videoUrl;
                        }
                    }

                    $categoryClass = strtolower(trim($row['category'] ?? 'other'));
                    $categoryClass = preg_replace('/[^a-z0-9_-]/', '', $categoryClass);
                    ?>
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="highlight-card">
                            <div class="highlight-card-body">
                                <div class="highlight-card-top">
                                    <span class="highlight-badge <?php echo $categoryClass; ?>">
                                        <?php echo htmlspecialchars($row['category']); ?>
                                    </span>
                                </div>

                                <h3 class="highlight-card-title">
                                    <?php echo htmlspecialchars($row['title']); ?>
                                </h3>

                                <?php if ($embedUrl !== ''): ?>
                                    <div class="highlight-video-wrap">
                                        <div class="ratio ratio-16x9">
                                            <iframe
                                                src="<?php echo htmlspecialchars($embedUrl); ?>"
                                                title="<?php echo htmlspecialchars($row['title']); ?>"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen>
                                            </iframe>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="highlight-video-unavailable">
                                        <div class="highlight-video-unavailable-text">
                                            This video cannot be embedded.
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="highlight-card-footer">
                                    <a
                                        href="<?php echo htmlspecialchars($watchUrl); ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="highlight-watch-btn"
                                    >
                                        Watch on YouTube
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="highlights-empty">
                        No highlights found.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
if (isset($stmt) && $stmt) {
    mysqli_stmt_close($stmt);
}

include 'components/footer.php';
?>