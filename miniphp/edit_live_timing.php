<?php
include 'db.php';

$current_page = 'admin_live_timing.php';
include 'components/admin_header.php';

$error = '';
$success = '';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$allowed_statuses = ['Running', 'Pit', 'Finished'];

if ($id <= 0) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>ไม่พบรหัสข้อมูล Live Timing</div></div>";
    include 'components/admin_footer.php';
    exit();
}

$stmt = mysqli_prepare($connection, "SELECT * FROM live_timing WHERE live_id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$row) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>ไม่พบข้อมูล Live Timing</div></div>";
    include 'components/admin_footer.php';
    exit();
}

$races_query = "
    SELECT r.raceid, r.racename, c.circuitname
    FROM races r
    LEFT JOIN circuits c ON r.circuitid = c.circuitid
    ORDER BY r.date ASC, r.raceid ASC
";
$races_result = mysqli_query($connection, $races_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $race_id = trim($_POST['race_id'] ?? '');
    $driver_name = trim($_POST['driver_name'] ?? '');
    $team_name = trim($_POST['team_name'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $lap_time = trim($_POST['lap_time'] ?? '');
    $points = trim($_POST['points'] ?? '0');
    $status = trim($_POST['status'] ?? 'Running');

    if (
        $race_id === '' ||
        $driver_name === '' ||
        $team_name === '' ||
        $position === '' ||
        $points === ''
    ) {
        $error = 'กรุณากรอกข้อมูลที่จำเป็นให้ครบ';
    } elseif (!in_array($status, $allowed_statuses, true)) {
        $error = 'สถานะไม่ถูกต้อง';
    } else {
        $race_id_int = (int)$race_id;
        $position_int = (int)$position;
        $points_int = (int)$points;

        $update = mysqli_prepare(
            $connection,
            "UPDATE live_timing
             SET race_id = ?, driver_name = ?, team_name = ?, position = ?, lap_time = ?, points = ?, status = ?
             WHERE live_id = ?"
        );

        if ($update) {
            mysqli_stmt_bind_param(
                $update,
                "issisisi",
                $race_id_int,
                $driver_name,
                $team_name,
                $position_int,
                $lap_time,
                $points_int,
                $status,
                $id
            );

            if (mysqli_stmt_execute($update)) {
                $success = 'อัปเดตข้อมูลเรียบร้อยแล้ว';

                $row['race_id'] = $race_id_int;
                $row['driver_name'] = $driver_name;
                $row['team_name'] = $team_name;
                $row['position'] = $position_int;
                $row['lap_time'] = $lap_time;
                $row['points'] = $points_int;
                $row['status'] = $status;
            } else {
                $error = 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล';
            }

            mysqli_stmt_close($update);
        } else {
            $error = 'ไม่สามารถเตรียมคำสั่ง SQL ได้';
        }
    }
}
?>

<div class="container mt-4 mb-5">
    <header class="custom-header text-center py-4">
        <h1>Edit Live Timing</h1>
        <p>แก้ไขอันดับนักแข่งสำหรับสนามที่เลือก</p>
    </header>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="table-card p-4">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label>เลือกสนาม / รายการแข่ง</label>
                        <select name="race_id" class="form-control" required>
                            <option value="">-- เลือกสนาม --</option>
                            <?php if ($races_result && mysqli_num_rows($races_result) > 0): ?>
                                <?php while ($race = mysqli_fetch_assoc($races_result)): ?>
                                    <option value="<?php echo htmlspecialchars($race['raceid']); ?>" <?php echo ((int)$row['race_id'] === (int)$race['raceid']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($race['racename']); ?>
                                        <?php if (!empty($race['circuitname'])): ?>
                                            — <?php echo htmlspecialchars($race['circuitname']); ?>
                                        <?php endif; ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Driver</label>
                        <input type="text" name="driver_name" class="form-control" value="<?php echo htmlspecialchars($row['driver_name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Team</label>
                        <input type="text" name="team_name" class="form-control" value="<?php echo htmlspecialchars($row['team_name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Position</label>
                        <input type="number" name="position" class="form-control" value="<?php echo htmlspecialchars($row['position']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Lap Time</label>
                        <input type="text" name="lap_time" class="form-control" value="<?php echo htmlspecialchars($row['lap_time']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Points</label>
                        <input type="number" name="points" class="form-control" value="<?php echo htmlspecialchars($row['points']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <?php foreach ($allowed_statuses as $item): ?>
                                <option value="<?php echo htmlspecialchars($item); ?>" <?php echo ($row['status'] === $item) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($item); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="admin_live_timing.php" class="btn btn-secondary">Back</a>
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'components/admin_footer.php'; ?>