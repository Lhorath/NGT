<?php
if (!isset($_SESSION['user_id'])) { header('Location: ' . BASE_URL . 'login'); exit(); }
$user_id = $_SESSION['user_id'];
$user = null;
$user_roles = [];
$stmt_user = $conn->prepare("SELECT first_name, last_name, display_name, email, created_at FROM users WHERE id = ?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
if ($result_user->num_rows === 1) {
    $user = $result_user->fetch_assoc();
    $stmt_roles = $conn->prepare("SELECT r.name FROM roles r JOIN user_roles ur ON r.id = ur.role_id WHERE ur.user_id = ?");
    $stmt_roles->bind_param("i", $user_id);
    $stmt_roles->execute();
    $result_roles = $stmt_roles->get_result();
    while ($row = $result_roles->fetch_assoc()) { $user_roles[] = $row['name']; }
    $stmt_roles->close();
} else { header('Location: ' . BASE_URL . 'logout'); exit(); }
$stmt_user->close();

if ($user) {
    $email = strtolower(trim($user['email']));
    $hash = md5($email);
    $gravatar_url = "https://www.gravatar.com/avatar/{$hash}?s=100&d=mp";
}
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <?php display_flash_message(); ?>
        <?php if ($user): ?>
        <div class="card">
            <div class="card-header d-flex align-items-center gap-3">
                <img src="<?php echo $gravatar_url; ?>" alt="<?php echo htmlspecialchars($user['display_name']); ?>'s Avatar" class="rounded-circle">
                <div>
                    <h2 class="h4 mb-0"><?php echo htmlspecialchars($user['display_name']); ?></h2>
                    <p class="text-muted mb-0">Member Since: <?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
                </div>
            </div>
            <div class="card-body">
                <h5 class="card-title">Account Details</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Full Name:</strong>
                        <span><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Email Address:</strong>
                        <span><?php echo htmlspecialchars($user['email']); ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Roles:</strong>
                        <span><?php echo htmlspecialchars(implode(', ', $user_roles)); ?></span>
                    </li>
                </ul>
            </div>
            <div class="card-footer text-center">
                <a href="<?php echo BASE_URL; ?>edit-profile" class="btn btn-outline-warning"><i class="fas fa-edit me-2"></i>Edit Profile</a>
                <a href="<?php echo BASE_URL; ?>change-password" class="btn btn-outline-secondary"><i class="fas fa-key me-2"></i>Change Password</a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
