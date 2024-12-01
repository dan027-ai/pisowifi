<?php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getDBConnection();
    
    $voucherId = $_POST['voucherId'];
    $phoneNumber = $_POST['phoneNumber'];
    $email = $_POST['email'];
    $amount = $_POST['price'];
    $paymentMethod = $_POST['paymentMethod'];
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Check if voucher exists and has remaining quantity
        $checkStmt = $conn->prepare("SELECT quantity_limit, remaining_quantity FROM vouchers WHERE id = ? FOR UPDATE");
        $checkStmt->bind_param("i", $voucherId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $voucher = $result->fetch_assoc();
        $checkStmt->close();
        
        // If voucher has quantity limit and no remaining quantity, return error
        if ($voucher['quantity_limit'] !== null && $voucher['remaining_quantity'] <= 0) {
            throw new Exception("Voucher out of stock");
        }
        
        // Insert transaction
        $stmt = $conn->prepare("INSERT INTO transactions (voucher_id, phone_number, email, amount, payment_method, status) VALUES (?, ?, ?, ?, ?, 'completed')");
        $stmt->bind_param("issds", $voucherId, $phoneNumber, $email, $amount, $paymentMethod);
        $stmt->execute();
        $stmt->close();
        
        // Update remaining quantity if voucher has quantity limit
        if ($voucher['quantity_limit'] !== null) {
            $updateStmt = $conn->prepare("UPDATE vouchers SET remaining_quantity = remaining_quantity - 1 WHERE id = ?");
            $updateStmt->bind_param("i", $voucherId);
            $updateStmt->execute();
            $updateStmt->close();
        }
        
        // Commit transaction
        $conn->commit();
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>