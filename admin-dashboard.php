<?php
session_start();
require_once 'config/database.php';
require_once 'components/admin/Header.php';
require_once 'components/admin/VoucherForm.php';
require_once 'components/admin/VouchersTable.php';
require_once 'components/admin/TransactionsTable.php';
require_once 'components/admin/TotalSales.php';
require_once 'components/admin/SalesChart.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: admin-login.php");
    exit;
}

// Get database connection
$conn = getDBConnection();

// Handle voucher operations
if (isset($_POST['delete_voucher'])) {
    $id = $_POST['voucher_id'];
    
    // Instead of deleting, set is_active to 0
    $stmt = $conn->prepare("UPDATE vouchers SET is_active = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    
    // Redirect to refresh the page
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['save_voucher'])) {
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $description = $_POST['description'];
    $is_promo = isset($_POST['is_promo']) ? 1 : 0;
    $promo_end_time = !empty($_POST['promo_end_time']) ? $_POST['promo_end_time'] : null;
    $quantity_limit = !empty($_POST['quantity_limit']) ? (int)$_POST['quantity_limit'] : null;
    $remaining_quantity = $quantity_limit; // Set initial remaining quantity
    
    if (isset($_POST['voucher_id']) && !empty($_POST['voucher_id'])) {
        // Update existing voucher
        $id = $_POST['voucher_id'];
        $stmt = $conn->prepare("UPDATE vouchers SET price = ?, duration = ?, description = ?, is_promo = ?, promo_end_time = ?, quantity_limit = ? WHERE id = ?");
        $stmt->bind_param("dssiisi", $price, $duration, $description, $is_promo, $promo_end_time, $quantity_limit, $id);
    } else {
        // Add new voucher
        $stmt = $conn->prepare("INSERT INTO vouchers (price, duration, description, is_promo, promo_end_time, quantity_limit, remaining_quantity, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
        $stmt->bind_param("dssisii", $price, $duration, $description, $is_promo, $promo_end_time, $quantity_limit, $remaining_quantity);
    }
    
    $stmt->execute();
    $stmt->close();
    
    // Redirect to refresh the page
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch active vouchers only
$result = $conn->query("SELECT v.*, 
    (SELECT COUNT(*) FROM transactions WHERE voucher_id = v.id) as times_used,
    COALESCE(v.remaining_quantity, v.quantity_limit) as remaining_quantity 
    FROM vouchers v 
    WHERE v.is_active = 1 
    ORDER BY v.price ASC");
$vouchers = $result->fetch_all(MYSQLI_ASSOC);

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
    <script>
        if (localStorage.getItem('darkMode') === 'dark') {
            document.documentElement.classList.add('dark');
        }

        function toggleDarkMode() {
            const html = document.documentElement;
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('darkMode', isDark ? 'dark' : 'light');
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
        .dark .text-gray-700 {
            color: #e2e8f0;
        }
        .dark input, .dark select {
            background-color: rgba(30, 41, 59, 0.8);
            color: #e2e8f0;
            border-color: #4b5563;
        }
        .dark .border-gray-200 {
            border-color: rgba(75, 85, 99, 0.3);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-b from-blue-50 to-white dark:from-gray-900 dark:to-gray-800 transition-colors duration-200">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(200,200,255,0.07)\"%3E%3C/path%3E%3C/svg%3E')] opacity-25"></div>

    <!-- Dark Mode Toggle Button -->
    <div class="fixed top-4 right-4">
        <button onclick="toggleDarkMode()" 
                class="p-2 rounded-lg bg-gray-800/80 hover:bg-gray-900 text-white backdrop-blur-sm transition-all duration-300 hover:scale-105">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>
    </div>

    <div class="container mx-auto py-8 px-4 relative">
        <?php renderAdminHeader(); ?>
        
        <!-- Dashboard Navigation -->
        <div class="mb-8">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button onclick="switchTab('overview')" id="overview-tab" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Overview
                    </button>
                    <button onclick="switchTab('vouchers')" id="vouchers-tab" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Vouchers Management
                    </button>
                    <button onclick="switchTab('transactions')" id="transactions-tab" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Transactions
                    </button>
                </nav>
            </div>
        </div>

        <!-- Overview Tab -->
        <div id="overview-content" class="tab-content">
            <div class="grid md:grid-cols-2 gap-8">
                <?php 
                renderSalesChart($conn);
                renderTotalSales($conn);
                ?>
            </div>
        </div>

        <!-- Vouchers Tab -->
        <div id="vouchers-content" class="tab-content hidden">
            <?php 
            renderVoucherForm();
            renderVouchersTable($vouchers);
            ?>
        </div>

        <!-- Transactions Tab -->
        <div id="transactions-content" class="tab-content hidden">
            <?php renderTransactionsTable($transactions); ?>
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

    function switchTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        // Remove active state from all tabs
        document.querySelectorAll('nav button').forEach(tab => {
            tab.classList.remove('border-indigo-500', 'text-indigo-600');
            tab.classList.add('border-transparent', 'text-gray-500');
        });

        // Show selected tab content
        document.getElementById(`${tabName}-content`).classList.remove('hidden');
        
        // Set active state for selected tab
        const activeTab = document.getElementById(`${tabName}-tab`);
        activeTab.classList.remove('border-transparent', 'text-gray-500');
        activeTab.classList.add('border-indigo-500', 'text-indigo-600');
    }

    // Set Overview as default active tab
    document.addEventListener('DOMContentLoaded', () => {
        switchTab('overview');
    });
    </script>
</body>
</html>
