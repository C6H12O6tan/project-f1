<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_selected'])) {
    $selectedSeats = $_POST['selected_seats'] ?? [];

    if (!empty($selectedSeats) && is_array($selectedSeats)) {
        $selectedSeats = array_map('intval', $selectedSeats);
        $selectedSeats = array_filter($selectedSeats, function ($id) {
            return $id > 0;
        });

        if (!empty($selectedSeats)) {
            $ids = implode(',', $selectedSeats);
            mysqli_query($connection, "DELETE FROM seating WHERE seatid IN ($ids)");
            header("Location: admin_seating.php?bulk_deleted=1");
            exit();
        }
    }
}

$limit = 20;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$page = ($page < 1) ? 1 : $page;
$offset = ($page - 1) * $limit;

$countResult = mysqli_query($connection, "SELECT COUNT(*) AS total FROM seating");
$countRow = mysqli_fetch_assoc($countResult);
$totalSeats = (int) ($countRow['total'] ?? 0);
$totalPages = (int) ceil($totalSeats / $limit);

$result = mysqli_query($connection, "
    SELECT
        s.seatid,
        s.ticketid,
        s.section,
        s.rownumber,
        s.seatnumber,
        s.status,
        t.category,
        t.seatmode,
        r.racename
    FROM seating s
    INNER JOIN tickets t ON s.ticketid = t.ticketid
    INNER JOIN races r ON t.raceid = r.raceid
    ORDER BY s.ticketid ASC, s.rownumber ASC, CAST(s.seatnumber AS UNSIGNED) ASC, s.seatnumber ASC
    LIMIT $limit OFFSET $offset
");

$current_page = 'admin_seating.php';
include 'components/admin_header.php';
?>

<header class="custom-header text-center py-4">
    <h1>Seating Management</h1>
    <p>ดูและจัดการที่นั่งทั้งหมดในระบบ</p>
</header>

<div class="container mt-5 mb-5">
    <div class="table-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <h4 class="mb-2">All Seats</h4>
            <a href="add_seat.php" class="btn btn-success mb-2">+ Generate Seats</a>
        </div>

        <?php if (isset($_GET['updated'])): ?>
            <div class="alert alert-success">Seat updated successfully.</div>
        <?php endif; ?>

        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success">Seat deleted successfully.</div>
        <?php endif; ?>

        <?php if (isset($_GET['bulk_deleted'])): ?>
            <div class="alert alert-success">Selected seats deleted successfully.</div>
        <?php endif; ?>

        <form method="POST" onsubmit="return confirm('Delete selected seats?');">
            <div class="mb-3" id="bulkActionWrap" style="display: none;">
                <button type="submit" name="delete_selected" class="btn btn-danger btn-sm">
                    Delete Selected
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width:40px;">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>Seat ID</th>
                            <th>Race</th>
                            <th>Ticket</th>
                            <th>Seat Mode</th>
                            <th>Section</th>
                            <th>Row</th>
                            <th>Seat</th>
                            <th>Status</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && mysqli_num_rows($result) > 0): ?>
                            <?php while ($seat = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="selected_seats[]" value="<?php echo (int) $seat['seatid']; ?>" class="seat-checkbox">
                                    </td>
                                    <td><?php echo (int) $seat['seatid']; ?></td>
                                    <td><?php echo htmlspecialchars($seat['racename']); ?></td>
                                    <td>
                                        #<?php echo (int) $seat['ticketid']; ?> -
                                        <?php echo htmlspecialchars($seat['category']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($seat['seatmode']); ?></td>
                                    <td><?php echo htmlspecialchars($seat['section']); ?></td>
                                    <td><?php echo htmlspecialchars($seat['rownumber']); ?></td>
                                    <td><?php echo htmlspecialchars(ltrim($seat['seatnumber'], '0') !== '' ? ltrim($seat['seatnumber'], '0') : $seat['seatnumber']); ?></td>
                                    <td><?php echo htmlspecialchars($seat['status']); ?></td>
                                    <td>
                                        <a href="edit_seat.php?seatid=<?php echo (int) $seat['seatid']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="delete_seat.php?seatid=<?php echo (int) $seat['seatid']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this seat?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center">No seating data found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </form>

        <?php if ($totalPages > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="admin_seating.php?page=<?php echo $page - 1; ?>">Previous</a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo ($page === $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="admin_seating.php?page=<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="admin_seating.php?page=<?php echo $page + 1; ?>">Next</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<script>
const selectAll = document.getElementById('selectAll');
const bulkActionWrap = document.getElementById('bulkActionWrap');

function updateBulkActionVisibility() {
    const checkedBoxes = document.querySelectorAll('.seat-checkbox:checked');
    if (checkedBoxes.length > 0) {
        bulkActionWrap.style.display = 'block';
    } else {
        bulkActionWrap.style.display = 'none';
    }
}

selectAll?.addEventListener('change', function () {
    const checkboxes = document.querySelectorAll('.seat-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
    updateBulkActionVisibility();
});

document.querySelectorAll('.seat-checkbox').forEach(cb => {
    cb.addEventListener('change', updateBulkActionVisibility);
});
</script>

<?php include 'components/admin_footer.php'; ?>