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
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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
    </style>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center transition-colors duration-200">
    <!-- Dark Mode Toggle Button -->
    <div class="fixed top-4 right-4">
        <button onclick="toggleDarkMode()" 
                class="p-2 rounded-lg bg-gray-800/80 hover:bg-gray-900 text-white backdrop-blur-sm transition-all duration-300 hover:scale-105">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>
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
</body>
</html>
