<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

session_regenerate_id(true);

include 'components/header.php';
?>

<div class="home-page">
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="hero-title">Ticket Management System F1</h1>
            <p class="hero-subtitle">จองตั๋วการแข่งขัน Formula 1 ได้ง่ายๆ</p>
        </div>
    </section>

    <section class="welcome-section">
        <div class="container text-center">
            <div class="welcome-box">
                <h2 class="welcome-title">ยินดีต้อนรับเข้าสู่ระบบ F1 Ticket Management</h2>
                <p class="welcome-text">
                    คุณสามารถเลือกจองตั๋ว ดูข่าวสารล่าสุด
                    และตรวจสอบรายการจองของคุณได้จากเมนูด้านบน
                </p>

                <div class="welcome-actions">
                    <a href="tickets.php" class="btn home-btn-primary">จองตั๋วเลย</a>
                    <a href="news.php" class="btn home-btn-secondary">ดูข่าวล่าสุด</a>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'components/footer.php'; ?>