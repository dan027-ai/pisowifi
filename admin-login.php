<?php
session_start();
require_once 'config/admin.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin-dashboard.php");
        exit;
    } else {
        $error = "Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-center mb-6">Admin Login</h1>
        
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 mb-2">Username</label>
                <input type="text" name="username" required 
                    class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            
            <div>
                <label class="block text-gray-700 mb-2">Password</label>
                <input type="password" name="password" required 
                    class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <button type="submit" 
                class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                Login
            </button>
        </form>
    </div>
</body>
</html>