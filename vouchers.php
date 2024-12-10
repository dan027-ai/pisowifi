<?php
require_once 'config/database.php';
require_once 'components/PaymentHeader.php';
require_once 'components/PaymentMethods.php';
require_once 'components/VoucherGrid.php';

// Get database connection
$conn = getDBConnection();

// Fetch only active vouchers from database
$result = $conn->query("SELECT *, 
    CASE 
        WHEN quantity_limit IS NOT NULL THEN quantity_limit - COALESCE((
            SELECT COUNT(*) 
            FROM transactions 
            WHERE voucher_id = vouchers.id AND status = 'completed'
        ), 0)
        ELSE NULL 
    END as remaining_quantity 
    FROM vouchers WHERE is_active = 1");
$vouchers = $result->fetch_all(MYSQLI_ASSOC);

// Get payment method from URL parameter, default to GCash
$paymentMethod = isset($_GET['method']) ? $_GET['method'] : 'gcash';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voucher_id = $_POST['voucherId'];
    $phone_number = $_POST['phoneNumber'];
    $email = $_POST['email'];
    $amount = $_POST['price'];
    $payment_method = $_POST['paymentMethod'];

    // Check if voucher has remaining quantity
    $stmt = $conn->prepare("SELECT quantity_limit, 
        (SELECT COUNT(*) FROM transactions WHERE voucher_id = ? AND status = 'completed') as used_quantity 
        FROM vouchers WHERE id = ?");
    $stmt->bind_param("ii", $voucher_id, $voucher_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $voucher_data = $result->fetch_assoc();
    
    if ($voucher_data['quantity_limit'] !== null && 
        $voucher_data['used_quantity'] >= $voucher_data['quantity_limit']) {
        echo json_encode(["success" => false, "error" => "Voucher quantity limit reached"]);
        exit;
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert transaction
        $stmt = $conn->prepare("INSERT INTO transactions (voucher_id, phone_number, email, amount, payment_method, status) VALUES (?, ?, ?, ?, ?, 'completed')");
        $stmt->bind_param("issds", $voucher_id, $phone_number, $email, $amount, $payment_method);
        $stmt->execute();

        // Update remaining_quantity in vouchers table
        $stmt = $conn->prepare("UPDATE vouchers 
            SET remaining_quantity = GREATEST(0, quantity_limit - (
                SELECT COUNT(*) 
                FROM transactions 
                WHERE voucher_id = ? AND status = 'completed'
            ))
            WHERE id = ? AND quantity_limit IS NOT NULL");
        $stmt->bind_param("ii", $voucher_id, $voucher_id);
        $stmt->execute();

        // Commit transaction
        $conn->commit();
        echo json_encode(["success" => true]);
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
    
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($paymentMethod); ?> Vouchers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        if (localStorage.getItem('darkMode') === 'dark') {
            document.documentElement.classList.add('dark');
        }

        function toggleDarkMode() {
            const html = document.documentElement;
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('darkMode', isDark ? 'dark' : 'light');
        }

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
    <style>
        .dark {
            color-scheme: dark;
        }
        .dark body {
            background: linear-gradient(to bottom, #1a1b26, #24283b);
            color: #ffffff;
        }
        .dark .bg-white {
            background-color: rgba(30, 41, 59, 0.8);
        }
        .dark .text-gray-600 {
            color: #94a3b8;
        }
        .dark .text-gray-700 {
            color: #e2e8f0;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50 transition-colors duration-200">
    <!-- Dark Mode Toggle Button -->
    <div class="fixed top-4 right-4 z-50">
        <button onclick="toggleDarkMode()" 
                class="p-2 rounded-lg bg-gray-800/80 hover:bg-gray-900 text-white backdrop-blur-sm transition-all duration-300 hover:scale-105">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>
    </div>

    <div id="modalOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40"></div>

    <div class="container mx-auto py-8">
        <!-- Back to Home Button -->
        <div class="mb-6">
            <a href="index.php" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Home
            </a>
        </div>

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
