<?php
// Allow CORS
header('Access-Control-Allow-Origin: http://localhost:5173');  // Allow requests from Vite dev server
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
    <div class="container mx-auto px-4 py-16">
        <div class="text-center space-y-8">
            <h1 class="text-5xl font-bold text-blue-600">Piso WiFi Connect</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Fast and affordable internet access. Purchase your WiFi voucher now and stay connected!
            </p>
            <div class="grid gap-8 md:grid-cols-3 max-w-4xl mx-auto mt-12">
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <div class="text-4xl text-blue-600 mb-4">⚡</div>
                    <h3 class="text-xl font-semibold mb-2">Fast Connection</h3>
                    <p class="text-gray-600">High-speed internet access for all your needs</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <div class="text-4xl text-blue-600 mb-4">💰</div>
                    <h3 class="text-xl font-semibold mb-2">Affordable Rates</h3>
                    <p class="text-gray-600">Starting from just ₱5 for 3 hours of access</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <div class="text-4xl text-blue-600 mb-4">🔒</div>
                    <h3 class="text-xl font-semibold mb-2">Secure Network</h3>
                    <p class="text-gray-600">Safe and encrypted connection for your privacy</p>
                </div>
            </div>
            <div class="mt-12">
                <a href="vouchers.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-xl px-8 py-6 rounded-lg transition-colors">
                    Buy WiFi Voucher Now
                </a>
            </div>
        </div>
    </div>
</body>
</html>
