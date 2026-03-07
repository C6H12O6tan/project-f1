<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$limit = 20;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

$total_query = "SELECT COUNT(*) AS total FROM bookings";
$total_result = mysqli_query($connection, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_pages = ceil($total_row['total'] / $limit);

$query = "SELECT b.bookingid, b.payment_date, b.paymentstatus, 
                 u.first_name, u.last_name, 
                 t.category, t.section
          FROM bookings b
          JOIN users u ON b.userid = u.user_id
          JOIN tickets t ON b.ticketid = t.ticketid
          ORDER BY b.bookingid ASC
          LIMIT $limit OFFSET $offset";
$result = mysqli_query($connection, $query);

$current_page = 'admin_bookings.php';
include 'components/admin_header.php';
?>

<header class="custom-header text-center py-4">
    <h1>Bookings Management</h1>
    <p>จัดการข้อมูลการจองทั้งหมด</p>
</header>

<div class="container mt-5">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Booking ID</th>
                    <th>Booker</th>
                    <th>Ticket</th>
                    <th>Booking Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['bookingid']); ?></td>
                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['category']) . ' / ' . htmlspecialchars($row['section']); ?></td>

                        <td>
                            <?php if (!empty($row['payment_date'])): ?>
                                <?php echo htmlspecialchars($row['payment_date']); ?>
                            <?php else: ?>
                                <span class="text-danger">N/A</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (!empty($row['paymentstatus'])): ?>
                                <?php echo htmlspecialchars($row['paymentstatus']); ?>
                            <?php else: ?>
                                <span class="text-danger">N/A</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <a href="edit_booking.php?bookingid=<?php echo urlencode($row['bookingid']); ?>" class="btn btn-sm btn-primary">
                                Edit
                            </a>
                            <a href="delete_booking.php?bookingid=<?php echo urlencode($row['bookingid']); ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('คุณต้องการลบรายการจองนี้หรือไม่?');">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php if ($total_pages > 1): ?>
        <nav>
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="admin_bookings.php?page=<?php echo ($page - 1); ?>">« Prev</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="admin_bookings.php?page=<?php echo $i; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="admin_bookings.php?page=<?php echo ($page + 1); ?>">Next »</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<?php include 'components/admin_footer.php'; ?>