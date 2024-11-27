<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "piso_wifi");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voucher_id = $_POST['voucherId'];
    $phone_number = $_POST['phoneNumber'];
    $email = $_POST['email'];
    $amount = $_POST['price'];
    $payment_method = $_POST['paymentMethod'];

    // Get voucher duration from database
    $stmt = $conn->prepare("SELECT duration FROM vouchers WHERE id = ?");
    $stmt->bind_param("i", $voucher_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $voucher = $result->fetch_assoc();
    
    // Calculate expiry time based on duration
    $duration_hours = intval($voucher['duration']);
    $expiry_time = date('Y-m-d H:i:s', strtotime("+{$duration_hours} hours"));

    // Simulate a short processing delay (2 seconds)
    sleep(2);

    $stmt = $conn->prepare("INSERT INTO transactions (voucher_id, phone_number, email, amount, payment_method, expiry_time) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdss", $voucher_id, $phone_number, $email, $amount, $payment_method, $expiry_time);
    
    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "expiryTime" => $expiry_time,
            "duration" => $voucher['duration'],
            "amount" => $amount
        ]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }
    
    $stmt->close();
    exit;
}

// Return error for non-POST requests
echo json_encode(["success" => false, "error" => "Invalid request method"]);
?>