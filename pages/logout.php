<?php
// Ensure session is started (though it should be from config.php)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Unset all of the session variables.
$_SESSION = [];

// Destroy the session cookie.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();

// Redirect to the homepage after logging out.
header('Location: ' . BASE_URL);
exit();
?>
