<?php
// Allow CORS
header('Access-Control-Allow-Origin: *');
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

switch ($first_segment) {
    case 'vouchers.php':
        require_once 'vouchers.php';
        break;
    default:
        // Serve the React application
        require_once 'index.html';
        break;
}
?>