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
define('BASE_URL', 'http://dab.nerdygamertools.com/NGT/');

define('DB_HOST', 'srv1846.hstgr.io');
define('DB_USER', 'u971098166_ngt_webdb');
define('DB_PASS', 'co?D=2O^eE2;');
define('DB_NAME', 'u971098166_ngt_webdb');

// 5. ERROR REPORTING (Set for Production)
// Set to 0 to hide errors from public users for security.
ini_set('display_errors', 0);
error_reporting(0);

// 6. CREATE DATABASE CONNECTION
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    // In production, you should log this error instead of showing it.
    // For now, we die silently to prevent info leaks.
    die("Database connection error.");
}
$conn->set_charset("utf8mb4");

?>
