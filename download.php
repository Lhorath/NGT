<?php
// Include the database configuration file to get access to $conn and sessions
require_once 'engine/config.php';

// --- SECURITY CHECK ---
// If a user_id is not set in the session, they are not logged in.
if (!isset($_SESSION['user_id'])) {
    // Set a flash message to inform the user why they were redirected.
    set_flash_message("You must be logged in to download files.", 'error');
    // Redirect them to the login page.
    header('Location: ' . BASE_URL . 'login');
    exit();
}

// --- 1. VALIDATE THE REQUEST ---
// Check if an ID is provided and if it's a valid number.
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: Invalid download link.");
}
$download_id = (int)$_GET['id'];


// --- 2. FETCH FILE INFORMATION FROM THE DATABASE ---
// Use a prepared statement to prevent SQL injection.
$stmt = $conn->prepare("SELECT file_path FROM downloads WHERE id = ?");
$stmt->bind_param("i", $download_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Error: File not found in database.");
}

$file_record = $result->fetch_assoc();
$filename = $file_record['file_path'];
$stmt->close();


// --- 3. CONSTRUCT FILE PATH AND PERFORM SECURITY CHECKS ---
$file_path = $_SERVER['DOCUMENT_ROOT'] . '/releases/' . $filename;

// Security: Prevent directory traversal attacks.
// Ensure the resolved file path is still within our 'releases' folder.
if (strpos(realpath($file_path), realpath($_SERVER['DOCUMENT_ROOT'] . '/releases/')) !== 0) {
    die("Error: Access denied.");
}

if (!file_exists($file_path)) {
    die("Error: File does not exist on server.");
}


// --- 4. INCREMENT THE DOWNLOAD COUNTER ---
// We do this *before* serving the file.
$stmt_update = $conn->prepare("UPDATE downloads SET download_count = download_count + 1 WHERE id = ?");
$stmt_update->bind_param("i", $download_id);
$stmt_update->execute();
$stmt_update->close();


// --- 5. SERVE THE FILE TO THE USER ---
// Set appropriate headers to trigger a download dialog in the browser.
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream'); // A generic binary file type
header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file_path));

// Clear output buffer
flush();

// Read the file and send its content to the browser
readfile($file_path);
exit;
?>
