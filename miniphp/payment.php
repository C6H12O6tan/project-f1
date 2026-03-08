<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$userId = (int) $_SESSION['user_id'];
$bookingId = isset($_GET['bookingid']) ? (int) $_GET['bookingid'] : 0;

if ($bookingId <= 0) {
    include 'components/header.php';
    echo '<main class="payment-page"><div class="container payment-container"><div class="payment-alert-error">Invalid booking selected.</div></div></main>';
    include 'components/footer.php';
    exit();
}

$stmt = mysqli_prepare($connection, "
    SELECT
        b.bookingid,
        b.userid,
        b.quantity,
        b.totalprice,
        b.paymentstatus,
        b.bookingdate,
        t.category,
        t.section,
        t.seatmode,
        r.racename,
        c.circuitname,
        c.location,
        c.country
    FROM bookings b
    JOIN tickets t ON b.ticketid = t.ticketid
    JOIN races r ON t.raceid = r.raceid
    JOIN circuits c ON r.circuitid = c.circuitid
    WHERE b.bookingid = ? AND b.userid = ?
    LIMIT 1
");
mysqli_stmt_bind_param($stmt, "ii", $bookingId, $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);

if (!$booking) {
    include 'components/header.php';
    echo '<main class="payment-page"><div class="container payment-container"><div class="payment-alert-error">Booking not found.</div></div></main>';
    include 'components/footer.php';
    exit();
}

function seatModeTextValue(string $seatmode): string
{
    $seatmode = strtolower(trim($seatmode));

    if ($seatmode === 'general') {
        return 'No assigned seat';
    }

    if ($seatmode === 'zoned') {
        return 'Reserved zone';
    }

    if ($seatmode === 'premium') {
        return 'Premium hospitality';
    }

    return 'Standard access';
}

$error = '';
$selectedMethod = $_POST['paymentmethod'] ?? 'Credit Card';

$cardName = trim($_POST['card_name'] ?? '');
$cardNumber = trim($_POST['card_number'] ?? '');
$expiryDate = trim($_POST['expiry_date'] ?? '');
$cvv = trim($_POST['cvv'] ?? '');

$currentBookingStatus = strtolower(trim((string) ($booking['paymentstatus'] ?? '')));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $allowedMethods = ['Credit Card', 'Bank Transfer', 'PayPal', 'PromptPay'];

    if (!in_array($selectedMethod, $allowedMethods, true)) {
        $error = 'Please select a valid payment method.';
    } elseif ($currentBookingStatus === 'paid') {
        $error = 'This booking has already been paid.';
    } elseif ($currentBookingStatus === 'cancelled' || $currentBookingStatus === 'canceled') {
        $error = 'This booking has already been cancelled.';
    } else {
        if ($selectedMethod === 'Credit Card') {
            if ($cardName === '' || $cardNumber === '' || $expiryDate === '' || $cvv === '') {
                $error = 'Please complete all credit card details.';
            } elseif (!preg_match('/^[A-Za-z\s]+$/', $cardName)) {
                $error = 'Cardholder name must contain English letters only.';
            } elseif (!preg_match('/^[0-9]{4}\s[0-9]{4}\s[0-9]{4}\s[0-9]{4}$/', $cardNumber)) {
                $error = 'Card number must be in format 1234 5678 9012 3456.';
            } elseif (!preg_match('/^(0[1-9]|1[0-2])\/[0-9]{2}$/', $expiryDate)) {
                $error = 'Expiry date must be in MM/YY format.';
            } elseif (!preg_match('/^[0-9]{3}$/', $cvv)) {
                $error = 'CVV must be 3 digits.';
            }
        }

        if ($error === '') {
            $newPaymentStatus = 'paid';
            $newBookingStatus = 'Paid';

            mysqli_begin_transaction($connection);

            try {
                $stmtInsertPayment = mysqli_prepare($connection, "
                    INSERT INTO payments (bookingid, paymentmethod, paymentstatus, paymentdate)
                    VALUES (?, ?, ?, NOW())
                ");
                mysqli_stmt_bind_param($stmtInsertPayment, "iss", $bookingId, $selectedMethod, $newPaymentStatus);

                if (!mysqli_stmt_execute($stmtInsertPayment)) {
                    throw new Exception('Failed to create payment record.');
                }

                $stmtUpdateBooking = mysqli_prepare($connection, "
                    UPDATE bookings
                    SET paymentstatus = ?
                    WHERE bookingid = ? AND userid = ?
                ");
                mysqli_stmt_bind_param($stmtUpdateBooking, "sii", $newBookingStatus, $bookingId, $userId);

                if (!mysqli_stmt_execute($stmtUpdateBooking)) {
                    throw new Exception('Failed to update booking status.');
                }

                mysqli_commit($connection);
                header("Location: bookings.php?paid=1");
                exit();
            } catch (Throwable $e) {
                mysqli_rollback($connection);
                $error = $e->getMessage();
            }
        }
    }
}

if (!empty($booking['bookingdate']) && strtotime($booking['bookingdate']) !== false) {
    $bookingDate = date('F j, Y', strtotime($booking['bookingdate']));
} else {
    $bookingDate = '-';
}

include 'components/header.php';
?>

<main class="payment-page">
    <div class="container payment-container">

        <section class="payment-header">
            <a href="bookings.php" class="payment-back-link">← Back to My Reservations</a>
            <h1 class="payment-title">Payment</h1>
            <p class="payment-subtitle">Choose your payment method and complete your booking.</p>
        </section>

        <?php if ($error !== ''): ?>
            <div class="payment-alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-7">
                <section class="payment-card">
                    <h2 class="payment-card-title"><?php echo htmlspecialchars($booking['racename']); ?></h2>

                    <div class="payment-info-grid">
                        <div class="payment-info-item">
                            <span class="payment-label">Location</span>
                            <span class="payment-value"><?php echo htmlspecialchars($booking['location'] . ', ' . $booking['country']); ?></span>
                        </div>

                        <div class="payment-info-item">
                            <span class="payment-label">Circuit</span>
                            <span class="payment-value"><?php echo htmlspecialchars($booking['circuitname']); ?></span>
                        </div>

                        <div class="payment-info-item">
                            <span class="payment-label">Category</span>
                            <span class="payment-value"><?php echo htmlspecialchars($booking['category']); ?></span>
                        </div>

                        <div class="payment-info-item">
                            <span class="payment-label">Section</span>
                            <span class="payment-value"><?php echo htmlspecialchars($booking['section']); ?></span>
                        </div>

                        <div class="payment-info-item">
                            <span class="payment-label">Access Type</span>
                            <span class="payment-value"><?php echo htmlspecialchars(seatModeTextValue($booking['seatmode'] ?? '')); ?></span>
                        </div>

                        <div class="payment-info-item">
                            <span class="payment-label">Quantity</span>
                            <span class="payment-value"><?php echo (int) $booking['quantity']; ?></span>
                        </div>

                        <div class="payment-info-item">
                            <span class="payment-label">Booking Date</span>
                            <span class="payment-value"><?php echo htmlspecialchars($bookingDate); ?></span>
                        </div>

                        <div class="payment-info-item">
                            <span class="payment-label">Current Status</span>
                            <span class="payment-value"><?php echo htmlspecialchars($booking['paymentstatus']); ?></span>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-lg-5">
                <section class="payment-summary-card">
                    <h2 class="payment-summary-title">Payment Summary</h2>

                    <div class="payment-summary-box">
                        <div class="payment-summary-row">
                            <span>Booking ID</span>
                            <span>#<?php echo (int) $booking['bookingid']; ?></span>
                        </div>

                        <div class="payment-summary-row">
                            <span>Ticket Type</span>
                            <span><?php echo htmlspecialchars($booking['category']); ?></span>
                        </div>

                        <div class="payment-summary-row">
                            <span>Section</span>
                            <span><?php echo htmlspecialchars($booking['section']); ?></span>
                        </div>

                        <div class="payment-summary-row">
                            <span>Quantity</span>
                            <span><?php echo (int) $booking['quantity']; ?></span>
                        </div>

                        <div class="payment-summary-row payment-summary-total">
                            <span>Total Price</span>
                            <span>฿<?php echo number_format((float) $booking['totalprice']); ?></span>
                        </div>
                    </div>

                    <?php if ($currentBookingStatus === 'paid'): ?>
                        <div class="payment-done-box">
                            This booking has already been paid.
                        </div>
                    <?php elseif ($currentBookingStatus === 'cancelled' || $currentBookingStatus === 'canceled'): ?>
                        <div class="payment-done-box payment-cancelled-box">
                            This booking has been cancelled.
                        </div>
                    <?php else: ?>
                        <form method="POST" class="payment-form">
                            <div class="payment-method-group">
                                <label class="payment-method-option">
                                    <input type="radio" name="paymentmethod" value="Credit Card" <?php echo $selectedMethod === 'Credit Card' ? 'checked' : ''; ?>>
                                    <span class="payment-method-card">
                                        <strong>Credit / Debit Card</strong>
                                        <small>Simulated payment</small>
                                    </span>
                                </label>

                                <div class="payment-method-panel <?php echo $selectedMethod === 'Credit Card' ? 'show' : ''; ?>" data-method-panel="Credit Card">
                                    <div class="payment-field">
                                        <label class="payment-field-label">Cardholder Name</label>
                                        <input
                                            type="text"
                                            name="card_name"
                                            class="payment-input"
                                            value="<?php echo htmlspecialchars($cardName); ?>"
                                            placeholder="John Doe"
                                            maxlength="60"
                                            pattern="[A-Za-z\s]+"
                                            title="Please use English letters only"
                                            autocomplete="cc-name"
                                        >
                                    </div>

                                    <div class="payment-field">
                                        <label class="payment-field-label">Card Number</label>
                                        <input
                                            type="text"
                                            name="card_number"
                                            id="card_number"
                                            class="payment-input"
                                            value="<?php echo htmlspecialchars($cardNumber); ?>"
                                            placeholder="1234 5678 9012 3456"
                                            maxlength="19"
                                            inputmode="numeric"
                                            autocomplete="cc-number"
                                        >
                                    </div>

                                    <div class="payment-inline-fields">
                                        <div class="payment-field">
                                            <label class="payment-field-label">Expiry Date</label>
                                            <input
                                                type="text"
                                                name="expiry_date"
                                                id="expiry_date"
                                                class="payment-input"
                                                value="<?php echo htmlspecialchars($expiryDate); ?>"
                                                placeholder="MM/YY"
                                                maxlength="5"
                                                inputmode="numeric"
                                                autocomplete="cc-exp"
                                            >
                                        </div>

                                        <div class="payment-field">
                                            <label class="payment-field-label">CVV</label>
                                            <input
                                                type="password"
                                                name="cvv"
                                                id="cvv"
                                                class="payment-input"
                                                value="<?php echo htmlspecialchars($cvv); ?>"
                                                placeholder="123"
                                                maxlength="3"
                                                inputmode="numeric"
                                                autocomplete="cc-csc"
                                            >
                                        </div>
                                    </div>
                                </div>

                                <label class="payment-method-option">
                                    <input type="radio" name="paymentmethod" value="Bank Transfer" <?php echo $selectedMethod === 'Bank Transfer' ? 'checked' : ''; ?>>
                                    <span class="payment-method-card">
                                        <strong>Bank Transfer</strong>
                                        <small>Simulated payment</small>
                                    </span>
                                </label>

                                <div class="payment-method-panel <?php echo $selectedMethod === 'Bank Transfer' ? 'show' : ''; ?>" data-method-panel="Bank Transfer">
                                    <div class="payment-bank-box">
                                        <p><strong>Bank:</strong> Bangkok Bank</p>
                                        <p><strong>Account Number:</strong> 123-4-56789-0</p>
                                        <p><strong>Account Name:</strong> F1 Ticket Management</p>
                                    </div>
                                </div>

                                <label class="payment-method-option">
                                    <input type="radio" name="paymentmethod" value="PayPal" <?php echo $selectedMethod === 'PayPal' ? 'checked' : ''; ?>>
                                    <span class="payment-method-card">
                                        <strong>PayPal</strong>
                                        <small>Simulated payment</small>
                                    </span>
                                </label>

                                <div class="payment-method-panel <?php echo $selectedMethod === 'PayPal' ? 'show' : ''; ?>" data-method-panel="PayPal">
                                    <div class="payment-bank-box">
                                        <p>You will confirm this payment as a PayPal transaction simulation.</p>
                                    </div>
                                </div>

                                <label class="payment-method-option">
                                    <input type="radio" name="paymentmethod" value="PromptPay" <?php echo $selectedMethod === 'PromptPay' ? 'checked' : ''; ?>>
                                    <span class="payment-method-card">
                                        <strong>PromptPay</strong>
                                        <small>Simulated payment</small>
                                    </span>
                                </label>

                                <div class="payment-method-panel <?php echo $selectedMethod === 'PromptPay' ? 'show' : ''; ?>" data-method-panel="PromptPay">
                                    <div class="payment-bank-box payment-qr-box">
                                        <img src="qrcode.png" alt="PromptPay QR" class="qr-image">
                                        <p><strong>PromptPay:</strong> 081-234-5678</p>
                                    </div>
                                </div>
                            </div>

                            <div class="payment-method-note">
                                All payment methods in this system are simulated.
                                Once you confirm the payment, the booking will be marked as <strong>Paid</strong>.
                            </div>

                            <button type="submit" class="payment-confirm-btn">
                                Confirm Payment
                            </button>
                        </form>
                    <?php endif; ?>
                </section>
            </div>
        </div>

    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('input[name="paymentmethod"]');
    const panels = document.querySelectorAll('.payment-method-panel');

    const cardNumberInput = document.getElementById('card_number');
    const expiryInput = document.getElementById('expiry_date');
    const cvvInput = document.getElementById('cvv');

    function togglePanels() {
        const selected = document.querySelector('input[name="paymentmethod"]:checked');
        const selectedValue = selected ? selected.value : '';

        panels.forEach(panel => {
            if (panel.getAttribute('data-method-panel') === selectedValue) {
                panel.classList.add('show');
            } else {
                panel.classList.remove('show');
            }
        });
    }

    radios.forEach(radio => {
        radio.addEventListener('change', togglePanels);
    });

    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function () {
            let value = this.value.replace(/\D/g, '').substring(0, 16);
            value = value.replace(/(.{4})/g, '$1 ').trim();
            this.value = value;
        });
    }

    if (expiryInput) {
        expiryInput.addEventListener('input', function () {
            let value = this.value.replace(/\D/g, '').substring(0, 4);

            if (value.length >= 3) {
                value = value.substring(0, 2) + '/' + value.substring(2);
            }

            this.value = value;
        });
    }

    if (cvvInput) {
        cvvInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').substring(0, 3);
        });
    }

    togglePanels();
});
</script>

<?php include 'components/footer.php'; ?>