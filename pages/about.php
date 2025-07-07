<?php
// --- Fetch Admin User's Email for Gravatar ---
// We'll assume the main admin is user_id = 1.
$gravatar_url = '';
$stmt = $conn->prepare("SELECT email FROM users WHERE id = 1");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 1) {
    $admin_user = $result->fetch_assoc();
    $email = strtolower(trim($admin_user['email']));
    $hash = md5($email);
    // Using a slightly larger size for the bio picture
    $gravatar_url = "https://www.gravatar.com/avatar/{$hash}?s=250&d=mp";
}
$stmt->close();
?>

<!-- Main Page Heading -->
<div class="text-center mb-5">
    <h2 class="display-5">About Nerdy Gamer Tools</h2>
    <p class="lead text-muted">Learn more about the creator and the project's philosophy.</p>
</div>


<!-- Section 1: About the Creator / Bio -->
<div class="card mb-5">
    <div class="card-body p-lg-5">
        <div class="row align-items-center">
            <!-- Gravatar Image -->
            <div class="col-lg-4 text-center mb-4 mb-lg-0">
                <?php if ($gravatar_url): ?>
                    <img src="<?php echo $gravatar_url; ?>" alt="Site Creator Avatar" class="img-fluid rounded-circle shadow-sm" style="max-width: 200px;">
                <?php endif; ?>
            </div>
            <!-- Bio Text -->
            <div class="col-lg-8">
                <h3>About the Creator</h3>
                <p class="text-secondary">
                    <!-- THIS IS THE SPOT FOR YOUR PERSONAL BIO -->
                    This is the spot for your personal bio. You can write a paragraph or two here about yourself, your passion for gaming and development, and what inspired you to create these tools. Let your personality shine through!
                </p>
                <div class="d-flex gap-3 mt-4">
                    <a href="#" class="footer-social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="footer-social-link"><i class="fab fa-github"></i></a>
                    <a href="#" class="footer-social-link"><i class="fab fa-discord"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Section 2: Website Philosophy -->
<div class="text-center mb-5">
    <h3>Our Philosophy</h3>
</div>
<div class="row text-center">
    <!-- Card: Modularity -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="mb-3"><i class="fas fa-puzzle-piece fa-3x text-warning"></i></div>
                <h5 class="card-title">Modularity</h5>
                <p class="card-text text-secondary">Building components that can be managed independently, like our page system, admin panel, and user roles.</p>
            </div>
        </div>
    </div>
    <!-- Card: Security -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="mb-3"><i class="fas fa-shield-alt fa-3x text-warning"></i></div>
                <h5 class="card-title">Security</h5>
                <p class="card-text text-secondary">Implementing safeguards like prepared statements, password hashing, and role-based access control.</p>
            </div>
        </div>
    </div>
    <!-- Card: Simplicity -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="mb-3"><i class="fas fa-magic fa-3x text-warning"></i></div>
                <h5 class="card-title">Simplicity</h5>
                <p class="card-text text-secondary">Keeping the core logic straightforward and the user interface clean and intuitive to use.</p>
            </div>
        </div>
    </div>
</div>


<!-- Section 3: Call to Action -->
<hr class="my-5">
<div class="text-center">
    <h4>Have a question or suggestion?</h4>
    <p class="text-muted">We'd love to hear from you. Get in touch via our contact page.</p>
    <a href="<?php echo BASE_URL; ?>contact" class="btn btn-warning mt-2"><i class="fas fa-envelope me-2"></i>Contact Us</a>
</div>
