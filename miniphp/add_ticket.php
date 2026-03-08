<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit();
}

$error = '';

$raceResult = mysqli_query($connection, "
    SELECT raceid, racename
    FROM races
    ORDER BY date ASC, raceid ASC
");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $raceid = isset($_POST['raceid']) ? (int) $_POST['raceid'] : 0;
    $category = trim($_POST['category'] ?? '');
    $section = trim($_POST['section'] ?? '');
    $seatmode = trim($_POST['seatmode'] ?? 'general');
    $price = isset($_POST['price']) ? (float) $_POST['price'] : 0;
    $totalseats = isset($_POST['totalseats']) ? (int) $_POST['totalseats'] : 0;

    $allowedSeatModes = ['general', 'zoned', 'premium'];

    $allowedSections = [
        'Walkabout' => ['General Admission', 'Zone A', 'Zone B', 'Zone C'],
        'Grandstand' => ['Main Grandstand', 'North Grandstand', 'South Grandstand', 'East Grandstand', 'West Grandstand'],
        'Hospitality' => ['Paddock Club', 'VIP Lounge', 'Champions Club', 'Premium Suite']
    ];

    if ($raceid <= 0) {
        $error = 'Please select a race.';
    } elseif ($category === '') {
        $error = 'Please select a category.';
    } elseif (!isset($allowedSections[$category])) {
        $error = 'Invalid category.';
    } elseif ($section === '') {
        $error = 'Please select a section.';
    } elseif (!in_array($section, $allowedSections[$category], true)) {
        $error = 'Invalid section for selected category.';
    } elseif (!in_array($seatmode, $allowedSeatModes, true)) {
        $error = 'Invalid seat mode.';
    } elseif ($price <= 0) {
        $error = 'Price must be greater than 0.';
    } elseif ($totalseats <= 0) {
        $error = 'Total seats must be greater than 0.';
    } else {
        $checkRaceStmt = mysqli_prepare($connection, "
            SELECT raceid
            FROM races
            WHERE raceid = ?
            LIMIT 1
        ");
        mysqli_stmt_bind_param($checkRaceStmt, "i", $raceid);
        mysqli_stmt_execute($checkRaceStmt);
        $checkRaceResult = mysqli_stmt_get_result($checkRaceStmt);
        $raceExists = mysqli_fetch_assoc($checkRaceResult);
        mysqli_stmt_close($checkRaceStmt);

        if (!$raceExists) {
            $error = 'Selected race does not exist.';
        } else {
            $availableseats = $totalseats;

            $insertStmt = mysqli_prepare($connection, "
                INSERT INTO tickets (raceid, category, section, price, totalseats, availableseats, seatmode)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            mysqli_stmt_bind_param(
                $insertStmt,
                "issdiis",
                $raceid,
                $category,
                $section,
                $price,
                $totalseats,
                $availableseats,
                $seatmode
            );

            if (mysqli_stmt_execute($insertStmt)) {
                mysqli_stmt_close($insertStmt);
                header("Location: admin_manage_tickets.php?added=1");
                exit();
            } else {
                $error = 'Failed to add ticket: ' . mysqli_error($connection);
            }

            mysqli_stmt_close($insertStmt);
        }
    }
}

$current_page = 'admin_manage_tickets.php';
include 'components/admin_header.php';

$selectedCategory = $_POST['category'] ?? '';
$selectedSection = $_POST['section'] ?? '';
$selectedSeatmode = $_POST['seatmode'] ?? '';
?>

<header class="custom-header text-center py-4">
    <h1>Add Ticket</h1>
    <p>เพิ่มประเภทตั๋วให้สอดคล้องกับระบบจริง</p>
</header>

<div class="container mt-5 mb-5">
    <div class="table-card p-4">
        <?php if ($error !== ''): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Race</label>
                <select class="form-control" name="raceid" required>
                    <option value="">-- Select Race --</option>
                    <?php if ($raceResult && mysqli_num_rows($raceResult) > 0): ?>
                        <?php while ($race = mysqli_fetch_assoc($raceResult)): ?>
                            <option value="<?php echo (int) $race['raceid']; ?>"
                                <?php echo (isset($_POST['raceid']) && (int) $_POST['raceid'] === (int) $race['raceid']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($race['racename']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select class="form-control" name="category" id="categorySelect" required>
                    <option value="">-- Select Category --</option>
                    <option value="Walkabout" <?php echo ($selectedCategory === 'Walkabout') ? 'selected' : ''; ?>>Walkabout</option>
                    <option value="Grandstand" <?php echo ($selectedCategory === 'Grandstand') ? 'selected' : ''; ?>>Grandstand</option>
                    <option value="Hospitality" <?php echo ($selectedCategory === 'Hospitality') ? 'selected' : ''; ?>>Hospitality</option>
                </select>
            </div>

            <div class="form-group">
                <label>Section / Block</label>
                <select class="form-control" name="section" id="sectionSelect" required>
                    <option value="">-- Select Section --</option>
                </select>
            </div>

            <div class="form-group">
                <label>Seat Mode</label>
                <select class="form-control" name="seatmode" required>
                    <option value="general" <?php echo ($selectedSeatmode === 'general') ? 'selected' : ''; ?>>General Admission (No seat selection)</option>
                    <option value="zoned" <?php echo ($selectedSeatmode === 'zoned') ? 'selected' : ''; ?>>Reserved Seating (Grandstand / Zoned)</option>
                    <option value="premium" <?php echo ($selectedSeatmode === 'premium') ? 'selected' : ''; ?>>Premium Reserved Seating</option>
                </select>
                <small class="form-text text-muted">
                    general = จองตามจำนวน, zoned/premium = ต้องเลือกที่นั่งจริง
                </small>
            </div>

            <div class="form-group">
                <label>Price</label>
                <input
                    type="number"
                    class="form-control"
                    name="price"
                    min="1"
                    step="0.01"
                    value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label>Total Seats</label>
                <input
                    type="number"
                    class="form-control"
                    name="totalseats"
                    min="1"
                    step="1"
                    value="<?php echo htmlspecialchars($_POST['totalseats'] ?? ''); ?>"
                    required
                >
                <small class="form-text text-muted">
                    Available seats will be set automatically equal to total seats.
                </small>
            </div>

            <button type="submit" class="btn btn-success">Add Ticket</button>
            <a href="admin_manage_tickets.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>

<script>
const sectionOptions = {
    Walkabout: ['General Admission', 'Zone A', 'Zone B', 'Zone C'],
    Grandstand: ['Main Grandstand', 'North Grandstand', 'South Grandstand', 'East Grandstand', 'West Grandstand'],
    Hospitality: ['Paddock Club', 'VIP Lounge', 'Champions Club', 'Premium Suite']
};

const categorySelect = document.getElementById('categorySelect');
const sectionSelect = document.getElementById('sectionSelect');
const selectedSection = <?php echo json_encode($selectedSection); ?>;

function renderSections() {
    const category = categorySelect.value;
    const sections = sectionOptions[category] || [];

    sectionSelect.innerHTML = '<option value="">-- Select Section --</option>';

    sections.forEach((section) => {
        const option = document.createElement('option');
        option.value = section;
        option.textContent = section;

        if (section === selectedSection) {
            option.selected = true;
        }

        sectionSelect.appendChild(option);
    });
}

categorySelect.addEventListener('change', function () {
    const currentCategory = categorySelect.value;
    const sections = sectionOptions[currentCategory] || [];

    sectionSelect.innerHTML = '<option value="">-- Select Section --</option>';

    sections.forEach((section) => {
        const option = document.createElement('option');
        option.value = section;
        option.textContent = section;
        sectionSelect.appendChild(option);
    });
});

renderSections();
</script>

<?php include 'components/admin_footer.php'; ?>