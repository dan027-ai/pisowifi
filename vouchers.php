<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "piso_wifi");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch vouchers from database
$result = $conn->query("SELECT * FROM vouchers");
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

// Include components
require_once 'components/PaymentHeader.php';
require_once 'components/PaymentMethods.php';
require_once 'components/VoucherGrid.php';
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

        <!-- Floating modal payment form -->
        <div id="paymentForm" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 max-w-md w-full bg-white p-6 rounded-lg shadow-xl hidden z-50">
            <button onclick="closePaymentForm()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h2 class="text-2xl font-bold mb-6 text-center">Complete Your Purchase</h2>
            <form id="purchaseForm" class="space-y-6">
                <input type="hidden" id="selectedVoucherId" name="voucherId">
                <input type="hidden" id="selectedPrice" name="price">
                <input type="hidden" id="paymentMethod" name="paymentMethod" value="<?php echo $paymentMethod; ?>">
                
                <div class="space-y-2">
                    <label class="block text-gray-700"><?php echo ucfirst($paymentMethod); ?> Phone Number</label>
                    <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="09XX XXX XXXX" 
                        class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div class="space-y-2">
                    <label class="block text-gray-700">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="your@email.com" 
                        class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <button type="submit" 
                    class="w-full <?php echo $paymentMethod === 'gcash' ? 'bg-gcash-blue hover:bg-gcash-secondary' : 'bg-paymaya-green hover:bg-paymaya-secondary'; ?> text-white py-2 px-4 rounded">
                    Pay ₱<span id="paymentAmount">0</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        function selectVoucher(id, price) {
            $('#selectedVoucherId').val(id);
            $('#selectedPrice').val(price);
            $('#paymentAmount').text(price);
            $('#paymentForm').removeClass('hidden');
            $('#modalOverlay').removeClass('hidden');
            document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
        }

        function closePaymentForm() {
            $('#paymentForm').addClass('hidden');
            $('#modalOverlay').addClass('hidden');
            document.body.style.overflow = ''; // Restore scrolling
        }

        $('#purchaseForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                voucherId: $('#selectedVoucherId').val(),
                phoneNumber: $('#phoneNumber').val(),
                email: $('#email').val(),
                price: $('#selectedPrice').val()
            };
            
            if (!formData.phoneNumber || !formData.email) {
                Swal.fire({
                    title: 'Error',
                    text: 'Please fill in all fields',
                    icon: 'error'
                });
                return;
            }

            Swal.fire({
                title: 'Processing payment...',
                text: 'Please wait while we process your payment.',
                icon: 'info',
                showConfirmButton: false
            });

            $.ajax({
                type: 'POST',
                url: 'vouchers.php',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        closePaymentForm();
                        Swal.fire({
                            title: 'Payment successful!',
                            text: 'Your voucher has been activated and your device is now connected.',
                            icon: 'success'
                        }).then(() => {
                            Swal.fire({
                                title: 'WiFi Connected!',
                                text: 'Your device is now connected to the network.',
                                icon: 'success'
                            });
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Payment failed. Please try again.',
                            icon: 'error'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'An error occurred. Please try again.',
                        icon: 'error'
                    });
                }
            });
        });

        // Close modal when clicking outside
        $('#modalOverlay').click(function() {
            closePaymentForm();
        });

        // Prevent modal from closing when clicking inside the form
        $('#paymentForm').click(function(e) {
            e.stopPropagation();
        });

        // Close modal when pressing ESC key
        $(document).keydown(function(e) {
            if (e.key === "Escape") {
                closePaymentForm();
            }
        });
    </script>
</body>
</html>