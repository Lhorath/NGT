<?php
// The generate_csrf_token() function is now called globally from config.php
$form_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token();
    $name = htmlspecialchars(trim($_POST['name']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    if (empty($name) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $form_message = '<div class="alert alert-danger">Please fill out all fields correctly.</div>';
    } else {
        $form_message = '<div class="alert alert-success"><h4 class="alert-heading">Thank you!</h4><p>Your message has been received. (This is a simulation - no email was actually sent.)</p></div>';
    }
}
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title mb-4">Contact Us</h2>
                <p class="text-muted mb-4">Have a question or want to work with us? Fill out the form below to get in touch!</p>
                <?php echo $form_message; ?>
                <?php if (strpos($form_message, 'alert-success') === false): ?>
                    <form action="<?php echo BASE_URL; ?>contact" method="POST">
                        <?php csrf_input(); ?>
                        <div class="mb-3"><label for="name" class="form-label">Your Name</label><input type="text" class="form-control" id="name" name="name" required></div>
                        <div class="mb-3"><label for="email" class="form-label">Your Email</label><input type="email" class="form-control" id="email" name="email" required></div>
                        <div class="mb-3"><label for="subject" class="form-label">Subject</label><input type="text" class="form-control" id="subject" name="subject" required></div>
                        <div class="mb-3"><label for="message" class="form-label">Message</label><textarea class="form-control" id="message" name="message" rows="6" required></textarea></div>
                        <button type="submit" class="btn btn-warning w-100"><i class="fas fa-paper-plane me-2"></i>Send Message</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
