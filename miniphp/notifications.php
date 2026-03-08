<?php
include 'components/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = (int) $_SESSION['user_id'];

$sql = "
    SELECT *
    FROM notifications
    WHERE user_id = ?
    ORDER BY created_at DESC, notification_id DESC
";

$stmt = mysqli_prepare($connection, $sql);

if (!$stmt) {
    echo "<div class='container mt-4 mb-5'><div class='alert alert-danger'>Failed to load notifications.</div></div>";
    include 'components/footer.php';
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">Notifications</h2>

        <a href="mark_notification_read.php?mode=all" class="btn btn-outline-dark btn-sm">
            Mark all as read
        </a>
    </div>

    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <div class="list-group">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <?php
                $notificationId = (int) $row['notification_id'];
                $title = $row['title'] ?? 'Notification';
                $message = $row['message'] ?? '';
                $link = $row['link'] ?? 'notifications.php';
                $isRead = (int) ($row['is_read'] ?? 0);
                $createdAt = !empty($row['created_at']) ? date('d M Y H:i', strtotime($row['created_at'])) : '-';
                ?>

                <div class="list-group-item py-3 <?php echo $isRead ? '' : 'list-group-item-light border-start border-4 border-danger'; ?>">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div class="me-3">
                            <h6 class="mb-1"><?php echo htmlspecialchars($title); ?></h6>
                            <p class="mb-2 text-muted"><?php echo htmlspecialchars($message); ?></p>
                            <small class="text-secondary"><?php echo htmlspecialchars($createdAt); ?></small>
                        </div>

                        <div class="d-flex flex-column align-items-end gap-2">
                            <?php if (!$isRead): ?>
                                <span class="badge bg-danger">Unread</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Read</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mt-3 d-flex gap-2 flex-wrap">
                        <a href="notification_redirect.php?id=<?php echo $notificationId; ?>" class="btn btn-danger btn-sm">
                            Open
                        </a>

                        <?php if (!$isRead): ?>
                            <a href="mark_notification_read.php?id=<?php echo $notificationId; ?>" class="btn btn-outline-secondary btn-sm">
                                Mark as read
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-light border">
            No notifications found.
        </div>
    <?php endif; ?>
</div>

<?php
mysqli_stmt_close($stmt);
include 'components/footer.php';
?>