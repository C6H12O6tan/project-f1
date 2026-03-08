<?php
include 'db.php';

$result = mysqli_query($connection, "
    SELECT *
    FROM live_timing
    ORDER BY position ASC, updated_at DESC
");

if (!$result) {
    echo '<tr><td colspan="6">Failed to load live timing data.</td></tr>';
    exit();
}

if (mysqli_num_rows($result) === 0) {
    echo '<tr><td colspan="6">No live timing data available.</td></tr>';
    exit();
}

while ($row = mysqli_fetch_assoc($result)) {
    $position = $row['position'] ?? '-';
    $driverName = $row['driver_name'] ?? '-';
    $teamName = $row['team_name'] ?? '-';
    $lapTime = $row['lap_time'] ?? '-';
    $points = $row['points'] ?? '0';
    $status = $row['status'] ?? '-';

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