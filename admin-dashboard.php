<?php
session_start();
require_once 'config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: admin-login.php");
    exit;
}

// Get database connection
$conn = getDBConnection();

// Handle voucher deletion
if (isset($_POST['delete_voucher'])) {
    $id = $_POST['voucher_id'];
    $stmt = $conn->prepare("DELETE FROM vouchers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Handle voucher addition/update
if (isset($_POST['save_voucher'])) {
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $description = $_POST['description'];
    $is_promo = isset($_POST['is_promo']) ? 1 : 0;
    $promo_end_time = $_POST['promo_end_time'] ?? null;
    $quantity_limit = $_POST['quantity_limit'] ? (int)$_POST['quantity_limit'] : null;
    
    if (isset($_POST['voucher_id']) && !empty($_POST['voucher_id'])) {
        // Update existing voucher
        $id = $_POST['voucher_id'];
        $stmt = $conn->prepare("UPDATE vouchers SET price = ?, duration = ?, description = ?, is_promo = ?, promo_end_time = ?, quantity_limit = ? WHERE id = ?");
        $stmt->bind_param("dssiisi", $price, $duration, $description, $is_promo, $promo_end_time, $quantity_limit, $id);
    } else {
        // Add new voucher
        $stmt = $conn->prepare("INSERT INTO vouchers (price, duration, description, is_promo, promo_end_time, quantity_limit) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("dssisi", $price, $duration, $description, $is_promo, $promo_end_time, $quantity_limit);
    }
    
    $stmt->execute();
    $stmt->close();
}

// Fetch all vouchers
$result = $conn->query("SELECT * FROM vouchers ORDER BY price ASC");
$vouchers = $result->fetch_all(MYSQLI_ASSOC);

// Fetch recent transactions
$transactions = $conn->query("SELECT t.*, v.duration FROM transactions t 
                            JOIN vouchers v ON t.voucher_id = v.id 
                            ORDER BY t.created_at DESC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50">
    <div class="container mx-auto py-8 px-4">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-blue-600">Admin Dashboard</h1>
            <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</a>
        </div>

        <!-- Add/Edit Voucher Form -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-xl font-bold mb-4">Add/Edit Voucher</h2>
            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="voucher_id" id="edit_voucher_id">
                <div>
                    <label class="block text-gray-700 mb-2">Price (₱)</label>
                    <input type="number" name="price" step="0.01" required 
                           class="w-full border border-gray-300 rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Duration</label>
                    <input type="text" name="duration" required 
                           class="w-full border border-gray-300 rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Description</label>
                    <input type="text" name="description" required 
                           class="w-full border border-gray-300 rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Promo Settings</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_promo" class="mr-2">
                            Is Promotional Voucher
                        </label>
                        <input type="datetime-local" name="promo_end_time" 
                               class="w-full border border-gray-300 rounded px-3 py-2"
                               placeholder="Promo End Time">
                        <input type="number" name="quantity_limit" 
                               class="w-full border border-gray-300 rounded px-3 py-2"
                               placeholder="Quantity Limit (optional)">
                    </div>
                </div>
                <div class="md:col-span-2">
                    <button type="submit" name="save_voucher" 
                            class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        Save Voucher
                    </button>
                </div>
            </form>
        </div>

        <!-- Vouchers Table -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-xl font-bold mb-4">Current Vouchers</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left">Price</th>
                            <th class="px-4 py-2 text-left">Duration</th>
                            <th class="px-4 py-2 text-left">Description</th>
                            <th class="px-4 py-2 text-left">Promo Status</th>
                            <th class="px-4 py-2 text-left">Remaining/Limit</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vouchers as $voucher): ?>
                        <tr class="border-t">
                            <td class="px-4 py-2">₱<?php echo $voucher['price']; ?></td>
                            <td class="px-4 py-2"><?php echo $voucher['duration']; ?></td>
                            <td class="px-4 py-2"><?php echo $voucher['description']; ?></td>
                            <td class="px-4 py-2">
                                <?php if ($voucher['is_promo']): ?>
                                    <span class="text-green-600">Active Promo</span>
                                    <?php if ($voucher['promo_end_time']): ?>
                                        <br>
                                        <span class="text-sm text-gray-500">
                                            Until: <?php echo date('Y-m-d H:i', strtotime($voucher['promo_end_time'])); ?>
                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-gray-500">Regular</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2">
                                <?php if ($voucher['quantity_limit']): ?>
                                    <?php echo $voucher['remaining_quantity'] ?? 0; ?>/<?php echo $voucher['quantity_limit']; ?>
                                <?php else: ?>
                                    Unlimited
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2">
                                <button onclick='editVoucher(<?php echo json_encode($voucher); ?>)'
                                        class="text-blue-600 hover:text-blue-800 mr-2">Edit</button>
                                <form method="POST" class="inline">
                                    <input type="hidden" name="voucher_id" value="<?php echo $voucher['id']; ?>">
                                    <button type="submit" name="delete_voucher" 
                                            class="text-red-600 hover:text-red-800">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">Recent Transactions</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left">Date</th>
                            <th class="px-4 py-2 text-left">Phone Number</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-left">Amount</th>
                            <th class="px-4 py-2 text-left">Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($transaction = $transactions->fetch_assoc()): ?>
                        <tr class="border-t">
                            <td class="px-4 py-2"><?php echo date('Y-m-d H:i', strtotime($transaction['created_at'])); ?></td>
                            <td class="px-4 py-2"><?php echo $transaction['phone_number']; ?></td>
                            <td class="px-4 py-2"><?php echo $transaction['email']; ?></td>
                            <td class="px-4 py-2">₱<?php echo $transaction['amount']; ?></td>
                            <td class="px-4 py-2"><?php echo $transaction['duration']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    function editVoucher(voucher) {
        document.querySelector('[name="voucher_id"]').value = voucher.id;
        document.querySelector('[name="price"]').value = voucher.price;
        document.querySelector('[name="duration"]').value = voucher.duration;
        document.querySelector('[name="description"]').value = voucher.description;
        document.querySelector('[name="is_promo"]').checked = voucher.is_promo == 1;
        if (voucher.promo_end_time) {
            document.querySelector('[name="promo_end_time"]').value = 
                new Date(voucher.promo_end_time).toISOString().slice(0, 16);
        } else {
            document.querySelector('[name="promo_end_time"]').value = '';
        }
        document.querySelector('[name="quantity_limit"]').value = voucher.quantity_limit || '';
        document.querySelector('[name="save_voucher"]').textContent = 'Update Voucher';
    }
    </script>
</body>
</html>