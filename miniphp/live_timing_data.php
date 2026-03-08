<?php
include 'db.php';

$sql = "
    SELECT lt1.*
    FROM live_timing lt1
    INNER JOIN (
        SELECT race_id, driver_name, MAX(updated_at) AS latest_updated
        FROM live_timing
        GROUP BY race_id, driver_name
    ) lt2
        ON lt1.race_id = lt2.race_id
        AND lt1.driver_name = lt2.driver_name
        AND lt1.updated_at = lt2.latest_updated
    ORDER BY lt1.position ASC, lt1.driver_name ASC
";

$result = mysqli_query($connection, $sql);

if (!$result) {
    echo '<tr><td colspan="6">Failed to load live timing data.</td></tr>';
    exit();
}

if (mysqli_num_rows($result) === 0) {
    echo '<tr><td colspan="6">No live timing data available.</td></tr>';
    exit();
}

while ($row = mysqli_fetch_assoc($result)) {
    $position   = $row['position'] ?? '-';
    $driverName = $row['driver_name'] ?? '-';
    $teamName   = $row['team_name'] ?? '-';
    $lapTime    = $row['lap_time'] ?? '-';
    $points     = $row['points'] ?? '0';
    $status     = $row['status'] ?? '-';

    echo '<tr>';
    echo '<td>' . htmlspecialchars($position) . '</td>';
    echo '<td>' . htmlspecialchars($driverName) . '</td>';
    echo '<td>' . htmlspecialchars($teamName) . '</td>';
    echo '<td>' . htmlspecialchars($lapTime) . '</td>';
    echo '<td>' . htmlspecialchars($points) . '</td>';
    echo '<td>' . htmlspecialchars($status) . '</td>';
    echo '</tr>';
}
?>