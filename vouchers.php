<?php
session_start();
require_once 'config/database.php';
require_once 'components/PaymentHeader.php';
require_once 'components/PaymentMethods.php';
require_once 'components/VoucherGrid.php';

// Set proper headers for JSON response when it's an API request
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

$conn = getDBConnection();

// Handle API requests
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Fetch vouchers
        $stmt = $conn->prepare("SELECT * FROM vouchers WHERE is_active = 1 AND (remaining_quantity > 0 OR remaining_quantity IS NULL)");
        $stmt->execute();
        $result = $stmt->get_result();
        $vouchers = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        echo json_encode(['success' => true, 'data' => $vouchers]);
        exit;
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Handle voucher purchase
        $data = json_decode(file_get_contents('php://input'), true);
        
        $voucher_id = $data['voucherId'];
        $phone_number = $data['phoneNumber'];
        $email = $data['email'];
        $amount = $data['price'];
        $payment_method = $data['paymentMethod'];

        $stmt = $conn->prepare("INSERT INTO transactions (voucher_id, phone_number, email, amount, payment_method) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issds", $voucher_id, $phone_number, $email, $amount, $payment_method);
        
        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $stmt->error]);
        }
        
        $stmt->close();
        exit;
    }
}

// For direct access, render the PHP page
// Fetch vouchers from database
$result = $conn->query("SELECT * FROM vouchers");
$vouchers = $result->fetch_all(MYSQLI_ASSOC);

// Get payment method from URL parameter, default to GCash
$paymentMethod = isset($_GET['method']) ? $_GET['method'] : 'gcash';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($paymentMethod); ?> Vouchers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gcash: {
                            blue: '#00ABE7',
                            secondary: '#0075C4'
                        },
                        paymaya: {
                            green: '#1C8C37',
                            secondary: '#156E2B'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Fixed overlay for the modal -->
    <div id="modalOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40"></div>

    <div class="container mx-auto py-8">
        <?php 
        renderHeader(
            ucfirst($paymentMethod) . " Vouchers",
            "Purchase vouchers quickly and securely using " . ucfirst($paymentMethod) . ". Select your preferred duration below."
        );
        
        renderPaymentMethods($paymentMethod);
        
        renderVoucherGrid($vouchers, $paymentMethod);
        ?>

        <!-- Payment Form Modal -->
        <?php require_once 'components/PaymentFormModal.php'; ?>
    </div>
    
    <!-- Payment Processing Scripts -->
    <?php require_once 'components/PaymentScripts.php'; ?>
</body>
</html>