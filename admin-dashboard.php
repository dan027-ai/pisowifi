<?php
session_start();
require_once 'config/database.php';
require_once 'components/admin/Header.php';
require_once 'components/admin/VoucherForm.php';
require_once 'components/admin/VouchersTable.php';
require_once 'components/admin/TransactionsTable.php';
require_once 'components/admin/TotalSales.php';
require_once 'components/admin/SalesChart.php';
require_once 'components/admin/DarkModeToggle.php';
require_once 'components/admin/DashboardStyles.php';
require_once 'components/admin/DashboardScripts.php';
require_once 'components/admin/Navigation.php';
require_once 'components/admin/Overview.php';

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
    $stmt = $conn->prepare("UPDATE vouchers SET is_active = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
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
    $remaining_quantity = $quantity_limit;
    
    if (isset($_POST['voucher_id']) && !empty($_POST['voucher_id'])) {
        $id = $_POST['voucher_id'];
        $stmt = $conn->prepare("UPDATE vouchers SET price = ?, duration = ?, description = ?, is_promo = ?, promo_end_time = ?, quantity_limit = ? WHERE id = ?");
        $stmt->bind_param("dssiisi", $price, $duration, $description, $is_promo, $promo_end_time, $quantity_limit, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO vouchers (price, duration, description, is_promo, promo_end_time, quantity_limit, remaining_quantity, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
        $stmt->bind_param("dssisii", $price, $duration, $description, $is_promo, $promo_end_time, $quantity_limit, $remaining_quantity);
    }
    
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch active vouchers
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
    <?php renderDashboardStyles(); ?>
</head>
<body class="min-h-screen bg-gradient-to-b from-blue-50 to-white dark:from-gray-900 dark:to-gray-800 transition-colors duration-200">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(200,200,255,0.07)\"%3E%3C/path%3E%3C/svg%3E')] opacity-25"></div>

    <?php renderDarkModeToggle(); ?>

    <div class="container mx-auto py-8 px-4 relative">
        <?php 
        renderAdminHeader();
        renderNavigation();
        ?>

        <!-- Tab Contents -->
        <?php renderOverview($conn); ?>

        <div id="vouchers-content" class="tab-content hidden">
            <?php 
            renderVoucherForm();
            renderVouchersTable($vouchers);
            ?>
        </div>

        <div id="transactions-content" class="tab-content hidden">
            <?php renderTransactionsTable($transactions); ?>
        </div>
    </div>
    
    <?php renderDashboardScripts(); ?>
</body>
</html>