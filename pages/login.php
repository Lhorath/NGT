<?php
// The generate_csrf_token() function is now called globally from config.php
$errors = [];
if (isset($_SESSION['user_id'])) { header('Location: ' . BASE_URL); exit(); }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token();
    if (empty($_POST['identifier']) || empty($_POST['password'])) {
        $errors[] = 'Please enter your username/email and password.';
    } else {
        $identifier = $_POST['identifier'];
        $password = $_POST['password'];
        $stmt = $conn->prepare("SELECT id, display_name, email, password FROM users WHERE email = ? OR display_name = ?");
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['display_name'] = $user['display_name'];
                $roles_stmt = $conn->prepare("SELECT r.name FROM roles r JOIN user_roles ur ON r.id = ur.role_id WHERE ur.user_id = ?");
                $roles_stmt->bind_param("i", $user['id']);
                $roles_stmt->execute();
                $roles_result = $roles_stmt->get_result();
                $_SESSION['roles'] = [];
                while ($role_row = $roles_result->fetch_assoc()) { $_SESSION['roles'][] = $role_row['name']; }
                $roles_stmt->close();
                header('Location: ' . BASE_URL);
                exit();
            }
        }
        $errors[] = 'Invalid credentials provided.';
        $stmt->close();
    }
}
?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-5">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center mb-0">User Login</h3>
            </div>
            <div class="card-body">
                <?php display_flash_message(); // To show "Registration successful" message ?>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error) { echo '<li>' . $error . '</li>'; } ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <form action="<?php echo BASE_URL; ?>login" method="POST">
                    <?php csrf_input(); ?>
                    <div class="mb-3">
                        <label for="identifier" class="form-label">Display Name or Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user fa-fw"></i></span>
                            <input type="text" class="form-control" id="identifier" name="identifier" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock fa-fw"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning"><i class="fas fa-sign-in-alt me-2"></i>Login</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <a href="<?php echo BASE_URL; ?>register">Don't have an account? Register</a>
            </div>
        </div>
    </div>
</div>
