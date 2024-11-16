<?php
$vouchers = [
    ['id' => 1, 'price' => 5, 'duration' => '3 hours', 'description' => 'Perfect for quick sessions'],
    ['id' => 2, 'price' => 10, 'duration' => '8 hours', 'description' => 'Great for all-day use'],
    ['id' => 3, 'price' => 15, 'duration' => '1 day', 'description' => '24 hours of unlimited access'],
    ['id' => 4, 'price' => 25, 'duration' => '2 days', 'description' => 'Weekend package'],
    ['id' => 5, 'price' => 50, 'duration' => '5 days', 'description' => 'Best value for longer periods'],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GCash Vouchers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="min-h-screen bg-gray-50">
    <div class="container mx-auto py-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-blue-600 mb-4">GCash Vouchers</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Purchase vouchers quickly and securely using GCash. Select your preferred duration below.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            <?php foreach ($vouchers as $voucher): ?>
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <div class="flex flex-col space-y-4">
                    <div class="text-2xl font-bold text-blue-600">
                        ₱<?php echo $voucher['price']; ?>
                    </div>
                    <div class="text-lg font-medium"><?php echo $voucher['duration']; ?></div>
                    <div class="text-sm text-gray-600"><?php echo $voucher['description']; ?></div>
                    <button 
                        onclick="selectVoucher(<?php echo $voucher['id']; ?>, <?php echo $voucher['price']; ?>)"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded transition-colors">
                        Select
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div id="paymentForm" class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-lg hidden">
            <h2 class="text-2xl font-bold mb-6 text-center">Complete Your Purchase</h2>
            <form id="purchaseForm" class="space-y-6">
                <input type="hidden" id="selectedVoucherId" name="voucherId">
                <input type="hidden" id="selectedPrice" name="price">
                
                <div class="space-y-2">
                    <label class="block text-gray-700">GCash Phone Number</label>
                    <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="09XX XXX XXXX" 
                        class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div class="space-y-2">
                    <label class="block text-gray-700">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="your@email.com" 
                        class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
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
            $('html, body').animate({
                scrollTop: $("#paymentForm").offset().top - 100
            }, 500);
        }

        $('#purchaseForm').on('submit', function(e) {
            e.preventDefault();
            
            const phoneNumber = $('#phoneNumber').val();
            const email = $('#email').val();
            
            if (!phoneNumber || !email) {
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

            // Simulate payment processing
            setTimeout(() => {
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
            }, 2000);
        });
    </script>
</body>
</html>