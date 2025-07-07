<?php
if (!isset($_SESSION['user_id'])) { header('Location: ' . BASE_URL . 'login'); exit(); }
// The generate_csrf_token() function is now called globally from config.php
$user_id = $_SESSION['user_id'];
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token();
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $new_password_confirm = $_POST['new_password_confirm'];
    if (empty($current_password) || empty($new_password) || empty($new_password_confirm)) { $errors[] = 'All fields are required.'; }
    elseif ($new_password !== $new_password_confirm) { $errors[] = 'New passwords do not match.'; }
    elseif (strlen($new_password) < 8) { $errors[] = 'New password must be at least 8 characters long.'; }
    if (empty($errors)) {
        $stmt_get = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt_get->bind_param("i", $user_id);
        $stmt_get->execute();
        $user = $stmt_get->get_result()->fetch_assoc();
        $stmt_get->close();
        if ($user && password_verify($current_password, $user['password'])) {
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt_update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt_update->bind_param("si", $new_password_hash, $user_id);
            if ($stmt_update->execute()) {
                set_flash_message('Your password has been changed successfully.', 'success');
                header('Location: ' . BASE_URL . 'profile');
                exit();
            } else { $errors[] = 'An error occurred while updating your password.'; }
            $stmt_update->close();
        } else { $errors[] = 'The current password you entered is incorrect.'; }
    }
}
?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card">
            <div class="card-header"><h3 class="mb-0">Change Your Password</h3></div>
            <div class="card-body">
                <?php if (!empty($errors)): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error) { echo '<li>' . $error . '</li>'; } ?></ul></div><?php endif; ?>
                <form action="<?php echo BASE_URL; ?>change-password" method="POST">
                    <?php csrf_input(); ?>
                    <div class="mb-3"><label for="current_password" class="form-label">Current Password</label><input type="password" class="form-control" id="current_password" name="current_password" required></div>
                    <div class="mb-3"><label for="new_password" class="form-label">New Password</label><input type="password" class="form-control" id="new_password" name="new_password" required minlength="8"><div class="form-text">Must be at least 8 characters long.</div></div>
                    <div class="mb-3"><label for="new_password_confirm" class="form-label">Confirm New Password</label><input type="password" class="form-control" id="new_password_confirm" name="new_password_confirm" required></div>
                    <div class="d-flex justify-content-end gap-2"><a href="<?php echo BASE_URL; ?>profile" class="btn btn-secondary">Cancel</a><button type="submit" class="btn btn-warning"><i class="fas fa-key me-2"></i>Change Password</button></div>
                </form>
            </div>
        </div>
    </div>
</div>
