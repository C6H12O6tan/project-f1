<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM tickets ORDER BY ticketid ASC";
$result = mysqli_query($connection, $query);

$current_page = 'admin_manage_tickets.php';
include 'components/admin_header.php';
?>

<header class="custom-header text-center py-4">
    <h1>Tickets Management</h1>
    <p>จัดการข้อมูลตั๋ว F1 ทั้งหมด</p>
</header>

<div class="container mt-5">
    <div class="mb-3 text-right">
        <a href="add_ticket.php" class="btn btn-success">+ เพิ่มตั๋ว</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Ticket ID</th>
                    <th>Category</th>
                    <th>Section</th>
                    <th>Price</th>
                    <th>Available Seats</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($ticket = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ticket['ticketid']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['category']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['section']); ?></td>
                        <td><?php echo number_format($ticket['price']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['availableseats']); ?></td>
                        <td>
                            <a href="edit_ticket.php?ticketid=<?php echo urlencode($ticket['ticketid']); ?>" class="btn btn-sm btn-primary">
                                Edit
                            </a>
                            <a href="delete_ticket.php?ticketid=<?php echo urlencode($ticket['ticketid']); ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('คุณต้องการลบตั๋วนี้หรือไม่?');">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'components/admin_footer.php'; ?>