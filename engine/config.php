<?php
// 1. START SESSION
session_start();

// 2. DEFINE ALL HELPER FUNCTIONS
function set_flash_message($message, $type) { $_SESSION['flash_message'] = ['message' => $message, 'type' => $type]; }
function display_flash_message() { if (isset($_SESSION['flash_message'])) { $message_data = $_SESSION['flash_message']; echo '<div class="alert alert-' . htmlspecialchars($message_data['type']) . '">' . htmlspecialchars($message_data['message']) . '</div>'; unset($_SESSION['flash_message']); } }
function generate_csrf_token() { if (empty($_SESSION['csrf_token'])) { $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); } }
function csrf_input() { echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token'] ?? '') . '">'; }
function validate_csrf_token() { if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) { die("CSRF token validation failed. Request rejected."); } }

// 3. RUN GLOBAL SETUP
generate_csrf_token();

// 4. DEFINE CONSTANTS FOR LIVE SERVER
define('BASE_URL', 'http://ngt.dackdns.ddns.net/');

define('DB_HOST', '127.0.0.1');
define('DB_USER', 'website');
define('DB_PASS', 'dax123');
define('DB_NAME', 'ngt_webdb');
define('DB_PORT', 3306);

// 5. ERROR REPORTING (Set for Production)
// Set to 0 to hide errors from public users for security.
ini_set('display_errors', 0);
error_reporting(0);

// 6. CREATE DATABASE CONNECTION
// PHP 8.1+ throws mysqli_sql_exception on failure; uncaught exceptions become HTTP 500.
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
} catch (mysqli_sql_exception $e) {
    error_log('NGT DB connect: ' . $e->getMessage());
    http_response_code(503);
    header('Content-Type: text/plain; charset=UTF-8');
    exit('Database connection error.');
}
if (!empty($conn->connect_error)) {
    error_log('NGT DB connect: ' . $conn->connect_error);
    http_response_code(503);
    header('Content-Type: text/plain; charset=UTF-8');
    exit('Database connection error.');
}
try {
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    error_log('NGT DB charset: ' . $e->getMessage());
    http_response_code(503);
    header('Content-Type: text/plain; charset=UTF-8');
    exit('Database connection error.');
}

?>
