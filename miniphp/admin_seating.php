<?php
include 'db.php';
include 'components/admin_header.php';

$limit = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = ($page < 1) ? 1 : $page;

$start = ($page - 1) * $limit;

$order = isset($_GET['order']) ? strtoupper($_GET['order']) : 'ASC';
$order = ($order === 'DESC') ? 'DESC' : 'ASC';
$new_order = ($order === 'ASC') ? 'DESC' : 'ASC';

$query = "SELECT * FROM seating 
          ORDER BY seatid $order 
          LIMIT $start, $limit";
$result = mysqli_query($connection, $query);

$total_query = "SELECT COUNT(*) AS total FROM seating";
$total_result = mysqli_query($connection, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_seats = $total_row['total'];
$total_pages = ceil($total_seats / $limit);
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Seating Management</h2>
    <p class="text-center text-muted mb-4">จัดการข้อมูลที่นั่งในสนาม</p>

    <div class="table-card">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <h4 class="mb-2">รายการที่นั่งทั้งหมด</h4>
            <a href="add_seat.php" class="btn btn-success mb-2">+ เพิ่มที่นั่ง</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>
                            <a href="?page=<?php echo $page; ?>&order=<?php echo $new_order; ?>" class="text-white">
                                Seat ID
                            </a>
                        </th>
                        <th>Zone</th>
                        <th>Row</th>
                        <th>Seat Number</th>
                        <th>Status</th>
                        <th width="160">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                        <?php while ($seat = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($seat['seatid']); ?></td>
                                <td><?php echo htmlspecialchars($seat['section']); ?></td>
                                <td><?php echo htmlspecialchars($seat['rownumber']); ?></td>
                                <td><?php echo htmlspecialchars($seat['seatnumber']); ?></td>
                                <td><?php echo htmlspecialchars($seat['status']); ?></td>
                                <td>
                                    <a href="edit_seat.php?seatid=<?php echo urlencode($seat['seatid']); ?>" class="btn btn-sm btn-primary">
                                        Edit
                                    </a>
                                    <a href="delete_seat.php?seatid=<?php echo urlencode($seat['seatid']); ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('คุณต้องการลบที่นั่งนี้หรือไม่?');">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">ยังไม่มีข้อมูลที่นั่ง</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($total_pages > 1): ?>
            <nav class="mt-4 mb-5">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>&order=<?php echo $order; ?>">
                            Previous
                        </a>
                    </li>

                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                        <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&order=<?php echo $order; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php } ?>

                    <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>&order=<?php echo $order; ?>">
                            Next
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<?php include 'components/admin_footer.php'; ?>