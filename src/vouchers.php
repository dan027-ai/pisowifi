<?php
// Set CORS headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:8080');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "piso_wifi");

if ($conn->connect_error) {
    die(json_encode([
        "success" => false,
        "error" => "Connection failed: " . $conn->connect_error
    ]));
}

// Handle GET request to fetch vouchers
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $result = $conn->query("SELECT * FROM vouchers WHERE is_active = 1");
    
    if ($result) {
        $vouchers = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode([
            "success" => true,
            "data" => $vouchers
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "error" => $conn->error
        ]);
    }
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voucher_id = $_POST['voucherId'];
    $phone_number = $_POST['phoneNumber'];
    $email = $_POST['email'];
    $amount = $_POST['price'];
    $payment_method = $_POST['paymentMethod'];
    $otp = $_POST['otp'] ?? '';

    // Verify OTP (in a real application, you would verify against a stored OTP)
    // For demo purposes, we'll accept any 4-digit OTP
    if (strlen($otp) !== 4) {
        echo json_encode(["success" => false, "error" => "Invalid OTP"]);
        exit;
    }

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

// Return error for other request methods
echo json_encode([
    "success" => false,
    "error" => "Invalid request method"
]);
?>
