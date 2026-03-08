<?php include 'components/header.php'; ?>

<div class="container mt-4 mb-5">
    <h2 class="mb-4">Live Timing</h2>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Position</th>
                    <th>Driver</th>
                    <th>Team</th>
                    <th>Time</th>
                    <th>Points</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="timingTable"></tbody>
        </table>
    </div>
</div>

<script>
function loadTiming() {
    fetch("live_timing_data.php")
        .then(response => response.text())
        .then(data => {
            document.getElementById("timingTable").innerHTML = data;
        })
        .catch(() => {
            document.getElementById("timingTable").innerHTML =
                '<tr><td colspan="6">Unable to load live timing data.</td></tr>';
        });
}

loadTiming();
setInterval(loadTiming, 10000);
</script>

<?php include 'components/footer.php'; ?>