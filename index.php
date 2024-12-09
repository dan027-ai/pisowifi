<?php
// Allow CORS
header('Access-Control-Allow-Origin: *');  // Allow requests from any origin during development
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle OPTIONS request for CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Route the request to the appropriate file
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Remove leading slash and get the first segment
$segments = explode('/', trim($path, '/'));
$first_segment = $segments[0];

// If it's an API request, route to the appropriate PHP file
if ($first_segment === 'vouchers.php') {
    require_once 'vouchers.php';
    exit;
}

// Otherwise, serve the React application
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piso WiFi Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="min-h-screen bg-gradient-to-b from-blue-50 to-white">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(200,200,255,0.07)\"%3E%3C/path%3E%3C/svg%3E')] opacity-25"></div>
    
    <div class="container mx-auto px-4 py-16 relative">
        <div class="text-center space-y-8">
            <h1 class="text-5xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-violet-600 filter drop-shadow-lg">
                Piso WiFi Connect
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto backdrop-blur-sm bg-white/30 p-4 rounded-xl">
                Fast and affordable internet access. Purchase your WiFi voucher now and stay connected!
            </p>
            
            <div class="grid gap-8 md:grid-cols-3 max-w-4xl mx-auto mt-12">
                <div class="bg-white/80 backdrop-blur-md p-6 rounded-xl shadow-lg border border-white/20 hover:transform hover:scale-105 transition-all duration-300">
                    <div class="text-4xl text-blue-600 mb-4">⚡</div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Fast Connection</h3>
                    <p class="text-gray-600">High-speed internet access for all your needs</p>
                </div>
                
                <div class="bg-white/80 backdrop-blur-md p-6 rounded-xl shadow-lg border border-white/20 hover:transform hover:scale-105 transition-all duration-300">
                    <div class="text-4xl text-blue-600 mb-4">💰</div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Affordable Rates</h3>
                    <p class="text-gray-600">Starting from just ₱5 for 3 hours of access</p>
                </div>
                
                <div class="bg-white/80 backdrop-blur-md p-6 rounded-xl shadow-lg border border-white/20 hover:transform hover:scale-105 transition-all duration-300">
                    <div class="text-4xl text-blue-600 mb-4">🔒</div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Secure Network</h3>
                    <p class="text-gray-600">Safe and encrypted connection for your privacy</p>
                </div>
            </div>
            
            <div class="mt-12">
                <a href="vouchers.php" 
                   class="inline-block bg-gradient-to-r from-blue-600 to-violet-600 hover:from-blue-700 hover:to-violet-700 text-white text-xl px-8 py-6 rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl backdrop-blur-sm">
                    Buy WiFi Voucher Now
                </a>
            </div>
        </div>
    </div>
    
    <!-- Admin Controls Button -->
    <div class="fixed bottom-8 right-8">
        <a href="admin-login.php" class="inline-flex items-center bg-gray-800/80 hover:bg-gray-900 text-white px-4 py-2 rounded-lg shadow-lg transition-all duration-300 backdrop-blur-sm hover:scale-105">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
            </svg>
            Admin Controls
        </a>
    </div>
</body>
</html>