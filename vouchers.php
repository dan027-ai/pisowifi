<?php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');
$conn = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle GET request to fetch vouchers
    $stmt = $conn->prepare("SELECT * FROM vouchers WHERE is_active = 1 AND (remaining_quantity > 0 OR remaining_quantity IS NULL)");
    $stmt->execute();
    $result = $stmt->get_result();
    $vouchers = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    echo json_encode(['success' => true, 'data' => $vouchers]);
    exit;
    
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode JSON input if content type is application/json
    if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $_POST = json_decode(file_get_contents('php://input'), true);
    }

    // Validate required fields
    if (!isset($_POST['voucherId']) || !isset($_POST['phoneNumber']) || !isset($_POST['email']) || !isset($_POST['price']) || !isset($_POST['paymentMethod'])) {
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
        exit;
    }

    $voucherId = $_POST['voucherId'];
    $phoneNumber = $_POST['phoneNumber'];
    $email = $_POST['email'];
    $amount = $_POST['price'];
    $paymentMethod = $_POST['paymentMethod'];
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Check if voucher exists and has remaining quantity
        $checkStmt = $conn->prepare("SELECT quantity_limit, remaining_quantity, price FROM vouchers WHERE id = ? AND is_active = 1 FOR UPDATE");
        $checkStmt->bind_param("i", $voucherId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $voucher = $result->fetch_assoc();
        $checkStmt->close();

        // Validate voucher exists
        if (!$voucher) {
            throw new Exception("Voucher not found or inactive");
        }

        // Validate price matches
        if (abs($voucher['price'] - $amount) > 0.01) {
            throw new Exception("Price mismatch");
        }
        
        // Check quantity limit
        if ($voucher['quantity_limit'] !== null) {
            if ($voucher['remaining_quantity'] <= 0) {
                throw new Exception("Voucher out of stock");
            }
            
            // Update remaining quantity
            $updateStmt = $conn->prepare("UPDATE vouchers SET remaining_quantity = remaining_quantity - 1 WHERE id = ? AND remaining_quantity > 0");
            $updateStmt->bind_param("i", $voucherId);
            if (!$updateStmt->execute() || $updateStmt->affected_rows === 0) {
                throw new Exception("Failed to update voucher quantity");
            }
            $updateStmt->close();
        }
        
        // Insert transaction
        $stmt = $conn->prepare("INSERT INTO transactions (voucher_id, phone_number, email, amount, payment_method, status) VALUES (?, ?, ?, ?, ?, 'completed')");
        $stmt->bind_param("issds", $voucherId, $phoneNumber, $email, $amount, $paymentMethod);
        if (!$stmt->execute()) {
            throw new Exception("Failed to create transaction");
        }
        $stmt->close();
        
        // Commit transaction
        $conn->commit();
        
        echo json_encode(['success' => true]);
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}

$conn->close();
?>