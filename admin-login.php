<?php
session_start();
require_once 'config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $conn = getDBConnection();
    
    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verify password - assuming passwords are stored as plain text for now
        // In production, you should use password_hash() and password_verify()
        if ($password === $user['password']) {
            $_SESSION['admin_logged_in'] = true;
            header("Location: admin-dashboard.php");
            exit;
        }
    }
    
    $error = "Invalid credentials";
    $stmt->close();
    $conn->close();
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
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Admin Login</h1>
            <a href="index.php" class="text-blue-600 hover:text-blue-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Home
            </a>
        </div>
        
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