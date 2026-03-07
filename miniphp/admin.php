<?php
include 'db.php';

$user_query = "SELECT COUNT(*) AS total_users FROM users";
$user_result = mysqli_query($connection, $user_query);
$user_data = $user_result ? mysqli_fetch_assoc($user_result) : ['total_users' => 0];
$total_users = $user_data['total_users'];

$ticket_query = "SELECT COUNT(*) AS total_tickets FROM tickets";
$ticket_result = mysqli_query($connection, $ticket_query);
$ticket_data = $ticket_result ? mysqli_fetch_assoc($ticket_result) : ['total_tickets' => 0];
$total_tickets = $ticket_data['total_tickets'];

$news_query = "SELECT COUNT(*) AS total_news FROM news";
$news_result = mysqli_query($connection, $news_query);
$news_data = $news_result ? mysqli_fetch_assoc($news_result) : ['total_news' => 0];
$total_news = $news_data['total_news'];

$highlight_query = "SELECT COUNT(*) AS total_highlights FROM highlights";
$highlight_result = mysqli_query($connection, $highlight_query);
$highlight_data = $highlight_result ? mysqli_fetch_assoc($highlight_result) : ['total_highlights' => 0];
$total_highlights = $highlight_data['total_highlights'];

include 'components/admin_header.php';
?>

<div class="container mt-5 mb-5">
    <h2 class="text-center mb-4">Dashboard Overview</h2>
    <p class="text-center text-muted mb-4">ภาพรวมข้อมูลของระบบ</p>

    <div class="row">
        <div class="col-md-6">
            <div class="card custom-card-color mb-4">
                <div class="card-header">จำนวนผู้ใช้ทั้งหมด</div>
                <div class="card-body">
                    <h3><?php echo htmlspecialchars($total_users); ?> คน</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card custom-card-color mb-4">
                <div class="card-header">จำนวนประเภทของตั๋วที่มีอยู่</div>
                <div class="card-body">
                    <h3><?php echo htmlspecialchars($total_tickets); ?> ประเภท</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card custom-card-color mb-4">
                <div class="card-header">จำนวนข่าวทั้งหมด</div>
                <div class="card-body">
                    <h3><?php echo htmlspecialchars($total_news); ?> ข่าว</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card custom-card-color mb-4">
                <div class="card-header">จำนวน Highlights</div>
                <div class="card-body">
                    <h3><?php echo htmlspecialchars($total_highlights); ?> รายการ</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'components/admin_footer.php'; ?>