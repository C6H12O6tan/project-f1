<?php
session_start();
include 'db.php';

$success = false;
$error_message = "";

if (!isset($_SESSION['user_id']) || !isset($_GET['bookingid'])) {
    header('Location: bookings.php');
    exit();
}

$bookingid = intval($_GET['bookingid']);
$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM bookings WHERE bookingid = ? AND userid = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "ii", $bookingid, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$booking) {
    $error_message = "ไม่พบข้อมูลการจอง!";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_method = mysqli_real_escape_string($connection, $_POST['payment_method']);
    $payment_date = date("Y-m-d H:i:s");

    if ($payment_method === "credit_card") {
        $updateQuery = "UPDATE bookings SET 
                        paymentstatus = 'paid', 
                        payment_method = ?, 
                        payment_date = ? 
                        WHERE bookingid = ?";
        $stmt = mysqli_prepare($connection, $updateQuery);
        mysqli_stmt_bind_param($stmt, "ssi", $payment_method, $payment_date, $bookingid);
        if (mysqli_stmt_execute($stmt)) {
            $success = true;
        } else {
            $error_message = "เกิดข้อผิดพลาด: " . mysqli_error($connection);
        }
        mysqli_stmt_close($stmt);
    } 
    else {
        $proof_field = ($payment_method === "bank_transfer") ? "payment_proof_bank" : "payment_proof";

        if (isset($_FILES[$proof_field]) && $_FILES[$proof_field]["error"] == 0) {
            $upload_dir = "uploads/slips/";

            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_name = time() . "_" . basename($_FILES[$proof_field]["name"]);
            $target_file = $upload_dir . $file_name;
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];
            $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (!in_array($file_extension, $allowed_extensions)) {
                $error_message = "❌ อัปโหลดได้เฉพาะไฟล์ JPG, PNG, PDF เท่านั้น!";
            }
            elseif ($_FILES[$proof_field]["size"] > 2 * 1024 * 1024) {
                $error_message = "❌ ไฟล์ต้องมีขนาดไม่เกิน 2MB!";
            }
            elseif (move_uploaded_file($_FILES[$proof_field]["tmp_name"], $target_file)) {
                $updateQuery = "UPDATE bookings SET 
                                paymentstatus = 'paid', 
                                payment_method = ?, 
                                payment_proof = ?, 
                                payment_date = ? 
                                WHERE bookingid = ?";
                $stmt = mysqli_prepare($connection, $updateQuery);
                mysqli_stmt_bind_param($stmt, "sssi", $payment_method, $file_name, $payment_date, $bookingid);
                if (mysqli_stmt_execute($stmt)) {
                    $success = true;
                } else {
                    $error_message = "เกิดข้อผิดพลาดในการอัปเดตฐานข้อมูล: " . mysqli_error($connection);
                }
                mysqli_stmt_close($stmt);
            } else {
                $error_message = "❌ ไม่สามารถอัปโหลดไฟล์ได้! กรุณาลองใหม่";
            }
        } else {
            $error_message = "❌ กรุณาอัปโหลดหลักฐานการชำระเงิน!";
        }
    }
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ชำระเงิน | F1 Ticket Management</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/pay.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark custom-navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">F1 Ticket Management</a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php if ($success): ?>
                    <div class="card w-50 mx-auto border-success shadow-lg">
                        <div class="card-body text-center">
                            <h5 class="card-title text-success">✅ การชำระเงินสำเร็จ!</h5>
                            <p class="card-text">หลักฐานการโอนของคุณถูกอัปโหลดเรียบร้อยแล้ว</p>
                            <a href="bookings.php" class="btn btn-success">ดูรายการจอง</a>
                        </div>
                    </div>
                <?php elseif (!empty($error_message)): ?>
                    <div class="card w-50 mx-auto border-danger shadow-lg">
                        <div class="card-body text-center">
                            <h5 class="card-title text-danger">❌ การชำระเงินล้มเหลว</h5>
                            <p class="card-text"><?= $error_message ?></p>
                            <a href="payment.php?bookingid=<?= $bookingid ?>" class="btn btn-danger">ลองอีกครั้ง</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="card w-75 mx-auto shadow-sm">
            <div class="card-body">
                <h5 class="card-title">เลือกวิธีชำระเงิน</h5>

                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="payment_method">เลือกวิธีชำระเงิน:</label>
                        <select class="form-control" name="payment_method" id="payment_method" required>
                            <option value="">-- กรุณาเลือก --</option>
                            <option value="credit_card">บัตรเครดิต/เดบิต</option>
                            <option value="bank_transfer">โอนเงินผ่านธนาคาร</option>
                            <option value="promptpay">พร้อมเพย์ (QR Code)</option>
                        </select>
                    </div>

                    <div id="credit_card_fields" class="d-none">
                        <div class="form-group">
                            <label>หมายเลขบัตร:</label>
                            <input type="text" class="form-control card-number" name="card_number"
                                placeholder="XXXX-XXXX-XXXX-XXXX" maxlength="19">
                        </div>
                        <div class="form-group">
                            <label>CVV:</label>
                            <input type="text" class="form-control cvv" name="cvv" placeholder="XXX" maxlength="3">
                        </div>
                        <div class="form-group">
                            <label>วันหมดอายุ:</label>
                            <input type="text" class="form-control expiry-date" name="expiry_date" placeholder="MM/YY"
                                maxlength="5">
                        </div>
                    </div>

                    <div id="bank_transfer_fields" class="d-none">
                        <p>**โอนเงินเข้าบัญชี**: <strong>123-456-7890 (ธนาคาร A)</strong></p>
                        <div class="form-group">
                            <label>อัปโหลดสลิปการโอนเงิน:</label>
                            <input type="file" class="form-control" id="payment_proof_bank" name="payment_proof_bank">
                        </div>
                    </div>

                    <div id="promptpay_qr" class="d-none text-center">
                        <p>**สแกน QR Code เพื่อชำระเงิน**</p>
                        <img src="qrcode.png?amount=1000" alt="QR Code" class="qr-image">
                        <div class="form-group mt-3">
                            <label>อัปโหลดสลิปการโอนเงิน:</label>
                            <input type="file" class="form-control" id="payment_proof_qr" name="payment_proof">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <button type="submit" class="btn btn-success w-25" id="submit_btn" disabled>
                            ยืนยันการชำระเงิน
                        </button>
                        <a href="bookings.php" class="btn btn-secondary w-25">ย้อนกลับ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
   $(document).ready(function () {
    $("#payment_method").change(function () {
        var method = $(this).val();
        $("#credit_card_fields").toggleClass("d-none", method !== "credit_card");
        $("#bank_transfer_fields").toggleClass("d-none", method !== "bank_transfer");
        $("#promptpay_qr").toggleClass("d-none", method !== "promptpay");
        validateForm();
    });

    $(".card-number").on("input", function () {
        var value = $(this).val().replace(/\D/g, "").substring(0, 16);
        var formatted = value.match(/.{1,4}/g);
        $(this).val(formatted ? formatted.join("-") : "");
        validateForm();
    });

    $(".cvv").on("input", function () {
        var value = $(this).val().replace(/\D/g, "").substring(0, 3);
        $(this).val(value);
        validateForm();
    });

    $(".expiry-date").on("input", function () {
        var value = $(this).val().replace(/\D/g, "").substring(0, 4);
        if (value.length > 2) {
            value = value.slice(0, 2) + "/" + value.slice(2);
        }
        $(this).val(value);
        validateForm();
    });

    $("#payment_proof_bank, #payment_proof_qr").change(function () {
        validateForm();
    });

    function validateForm() {
        var method = $("#payment_method").val();
        var cardNumber = $(".card-number").val() || "";
        var cvv = $(".cvv").val() || "";
        var expiry = $(".expiry-date").val() || "";

        var proofBank = $("#payment_proof_bank").val();
        var proofQR = $("#payment_proof_qr").val();

        var submitBtn = $("#submit_btn");

        if (method === "credit_card") {
            if (cardNumber.length === 19 && cvv.length === 3 && expiry.length === 5) {
                submitBtn.prop("disabled", false);
            } else {
                submitBtn.prop("disabled", true);
            }
        } else if (method === "bank_transfer") {
            if (proofBank !== "") {
                submitBtn.prop("disabled", false);
            } else {
                submitBtn.prop("disabled", true);
            }
        } else if (method === "promptpay") {
            if (proofQR !== "") {
                submitBtn.prop("disabled", false);
            } else {
                submitBtn.prop("disabled", true);
            }
        } else {
            submitBtn.prop("disabled", true);
        }
    }

    $("#payment_method").trigger("change");
});

    </script>

</body>
</html>
