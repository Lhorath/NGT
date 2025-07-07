<?php
if (!isset($_SESSION['user_id'])) { header('Location: ' . BASE_URL . 'login'); exit(); }
// The generate_csrf_token() function is now called globally from config.php
$user_id = $_SESSION['user_id'];
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token();
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $display_name = trim($_POST['display_name']);
    if (empty($first_name) || empty($last_name) || empty($display_name)) { $errors[] = "All fields are required."; }
    if (empty($errors)) {
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE display_name = ? AND id != ?");
        $stmt_check->bind_param("si", $display_name, $user_id);
        $stmt_check->execute();
        if ($stmt_check->get_result()->num_rows > 0) { $errors[] = "That display name is already taken."; }
        $stmt_check->close();
    }
    if (empty($errors)) {
        $stmt_update = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, display_name = ? WHERE id = ?");
        $stmt_update->bind_param("sssi", $first_name, $last_name, $display_name, $user_id);
        if ($stmt_update->execute()) {
            $_SESSION['display_name'] = $display_name;
            set_flash_message('Your profile has been updated successfully.', 'success');
            header('Location: ' . BASE_URL . 'profile');
            exit();
        } else { $errors[] = "An error occurred while updating your profile."; }
        $stmt_update->close();
    }
}
$stmt_get = $conn->prepare("SELECT first_name, last_name, display_name FROM users WHERE id = ?");
$stmt_get->bind_param("i", $user_id);
$stmt_get->execute();
$user = $stmt_get->get_result()->fetch_assoc();
$stmt_get->close();
if (!$user) { header('Location: ' . BASE_URL . 'logout'); exit(); }
?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card">
            <div class="card-header"><h3 class="mb-0">Edit Your Profile</h3></div>
            <div class="card-body">
                <?php if (!empty($errors)): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error) { echo '<li>' . $error . '</li>'; } ?></ul></div><?php endif; ?>
                <form action="<?php echo BASE_URL; ?>edit-profile" method="POST">
                    <?php csrf_input(); ?>
                     <div class="mb-3"><label for="first_name" class="form-label">First Name</label><input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required></div>
                    <div class="mb-3"><label for="last_name" class="form-label">Last Name</label><input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required></div>
                    <div class="mb-3"><label for="display_name" class="form-label">Display Name</label><input type="text" class="form-control" id="display_name" name="display_name" value="<?php echo htmlspecialchars($user['display_name']); ?>" required></div>
                    <div class="d-flex justify-content-end gap-2"><a href="<?php echo BASE_URL; ?>profile" class="btn btn-secondary">Cancel</a><button type="submit" class="btn btn-warning"><i class="fas fa-save me-2"></i>Save Changes</button></div>
                </form>
            </div>
        </div>
    </div>
</div>
