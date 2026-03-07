<?php
include 'db.php';

$current_page = 'admin_live_timing.php';
include 'components/admin_header.php';

$query = "
    SELECT 
        lt.*,
        r.racename,
        c.circuitname
    FROM live_timing lt
    LEFT JOIN races r ON lt.race_id = r.raceid
    LEFT JOIN circuits c ON r.circuitid = c.circuitid
    ORDER BY lt.race_id ASC, lt.position ASC, lt.live_id ASC
";
$result = mysqli_query($connection, $query);
?>

<header class="custom-header text-center py-4">
    <h1>Live Timing Management</h1>
    <p>จัดการอันดับนักแข่งแบบเรียลไทม์</p>
</header>

<div class="container mt-5 mb-5">
    <div class="table-card">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <h4 class="mb-2">รายการ Live Timing ทั้งหมด</h4>
            <a href="add_live_timing.php" class="btn btn-primary mb-2">+ เพิ่มข้อมูล</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>Race</th>
                        <th>Pos.</th>
                        <th>Driver</th>
                        <th>Team</th>
                        <th>Lap Time</th>
                        <th>Points</th>
                        <th>Status</th>
                        <th>Updated</th>
                        <th width="180">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td style="min-width: 220px;">
                                    <?php if (!empty($row['racename'])): ?>
                                        <strong><?php echo htmlspecialchars($row['racename']); ?></strong>
                                        <?php if (!empty($row['circuitname'])): ?>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($row['circuitname']); ?></small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">Race ID #<?php echo htmlspecialchars($row['race_id']); ?></span>
                                    <?php endif; ?>
                                </td>

                                <td><?php echo htmlspecialchars($row['position']); ?></td>
                                <td><strong><?php echo htmlspecialchars($row['driver_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['team_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['lap_time'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($row['points']); ?></td>
                                <td>
                                    <?php
                                    $status = $row['status'] ?? '';
                                    $badgeClass = 'badge-secondary';
                                    if ($status === 'Running') {
                                        $badgeClass = 'badge-success';
                                    } elseif ($status === 'Pit') {
                                        $badgeClass = 'badge-warning';
                                    } elseif ($status === 'Finished') {
                                        $badgeClass = 'badge-primary';
                                    }
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?>">
                                        <?php echo htmlspecialchars($status); ?>
                                    </span>
                                </td>
                                <td><?php echo !empty($row['updated_at']) ? htmlspecialchars($row['updated_at']) : '-'; ?></td>
                                <td>
                                    <a href="edit_live_timing.php?id=<?php echo urlencode($row['live_id']); ?>" class="btn btn-warning btn-sm mb-1">
                                        Edit
                                    </a>
                                    <a href="delete_live_timing.php?id=<?php echo urlencode($row['live_id']); ?>"
                                       class="btn btn-danger btn-sm mb-1"
                                       onclick="return confirm('ต้องการลบข้อมูลนี้หรือไม่?');">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">ยังไม่มีข้อมูล Live Timing</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'components/admin_footer.php'; ?>