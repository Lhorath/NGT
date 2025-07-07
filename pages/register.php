<?php
// The generate_csrf_token() function is now called globally from config.php
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token();
    $required_fields = ['first_name', 'last_name', 'display_name', 'email', 'password', 'password_confirm'];
    foreach ($required_fields as $field) { if (empty($_POST[$field])) { $errors[] = 'All fields are required.'; break; } }
    if (empty($errors)) {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { $errors[] = 'Please enter a valid email address.'; }
        if ($_POST['password'] !== $_POST['password_confirm']) { $errors[] = 'Passwords do not match.'; }
        if (strlen($_POST['password']) < 8) { $errors[] = 'Password must be at least 8 characters long.'; }
    }
    if (empty($errors)) {
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ? OR display_name = ?");
        $stmt_check->bind_param("ss", $_POST['email'], $_POST['display_name']);
        $stmt_check->execute();
        if ($stmt_check->get_result()->num_rows > 0) { $errors[] = 'An account with that email or display name already exists.'; }
        $stmt_check->close();
    }
    if (empty($errors)) {
        $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $conn->begin_transaction();
        try {
            $stmt_user = $conn->prepare("INSERT INTO users (first_name, last_name, display_name, email, password) VALUES (?, ?, ?, ?, ?)");
            $stmt_user->bind_param("sssss", $_POST['first_name'], $_POST['last_name'], $_POST['display_name'], $_POST['email'], $password_hash);
            $stmt_user->execute();
            $new_user_id = $conn->insert_id;
            $stmt_user->close();
            $default_role_id = 3;
            $stmt_role = $conn->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
            $stmt_role->bind_param("ii", $new_user_id, $default_role_id);
            $stmt_role->execute();
            $stmt_role->close();
            $conn->commit();
            set_flash_message('Registration successful! You can now log in.', 'success');
            header('Location: ' . BASE_URL . 'login');
            exit();
        } catch (mysqli_sql_exception $exception) {
            $conn->rollback();
            $errors[] = 'An error occurred. Please try again later.';
        }
    }
}
?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card">
            <div class="card-header"><h3 class="text-center mb-0">Create an Account</h3></div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error) { echo '<li>' . $error . '</li>'; } ?></ul></div>
                <?php endif; ?>
                <form action="<?php echo BASE_URL; ?>register" method="POST">
                    <?php csrf_input(); ?>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label for="first_name" class="form-label">First Name</label><input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" required></div>
                        <div class="col-md-6 mb-3"><label for="last_name" class="form-label">Last Name</label><input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" required></div>
                    </div>
                    <div class="mb-3"><label for="display_name" class="form-label">Display Name</label><input type="text" class="form-control" id="display_name" name="display_name" value="<?php echo htmlspecialchars($_POST['display_name'] ?? ''); ?>" required></div>
                    <div class="mb-3"><label for="email" class="form-label">Email</label><input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required></div>
                    <div class="mb-3"><label for="password" class="form-label">Password</label><input type="password" class="form-control" id="password" name="password" required minlength="8"><div class="form-text">Must be at least 8 characters long.</div></div>
                    <div class="mb-3"><label for="password_confirm" class="form-label">Confirm Password</label><input type="password" class="form-control" id="password_confirm" name="password_confirm" required></div>
                    <div class="d-grid"><button type="submit" class="btn btn-warning"><i class="fas fa-user-plus me-2"></i>Register</button></div>
                </form>
            </div>
            <div class="card-footer text-center"><a href="<?php echo BASE_URL; ?>login">Already have an account? Login</a></div>
        </div>
    </div>
</div>
