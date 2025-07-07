<?php
// --- CONTROLLER ---
require_once 'engine/config.php';

// The allowed pages array for the live server
$allowed_pages = ['home', 'contact', 'about', 'news', 'register', 'login', 'logout', 'profile', 'admin', 'projects', 'article', 'downloads', 'edit-profile', 'change-password', '404', 'search'];

$page = !empty($_GET['page']) ? $_GET['page'] : 'home';
$page_path = "pages/{$page}.php";

// Logic to handle 404 errors
if (!in_array($page, $allowed_pages) || !file_exists($page_path)) {
    header("HTTP/1.0 404 Not Found");
    $page = '404';
    $page_path = "pages/404.php";
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nerdy Gamer Tools &bull; <?php echo ucfirst($page); ?></title>

    <!-- Libraries (Bootstrap, Font Awesome) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Your Custom Stylesheet -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>STYLE/css/style.css">

    <!-- Custom styles for footer -->
    <style>
        .footer-social-link {
            display: inline-flex; justify-content: center; align-items: center;
            width: 40px; height: 40px; color: var(--bs-secondary-color);
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 50%; text-decoration: none; transition: all 0.2s ease-in-out;
        }
        .footer-social-link:hover {
            color: var(--bs-light); background-color: var(--bs-primary);
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg bg-dark navbar-dark sticky-top border-bottom border-secondary-subtle">
        <div class="container">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
                <img src="<?php echo BASE_URL; ?>STYLE/images/logo-nav.png" alt="Nerdy Gamer Tools Logo" style="height: 40px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link <?php if($page === 'news') echo 'active'; ?>" href="<?php echo BASE_URL; ?>news">News</a></li>
                    <li class="nav-item"><a class="nav-link <?php if($page === 'projects') echo 'active'; ?>" href="<?php echo BASE_URL; ?>projects">Projects</a></li>
                    <li class="nav-item"><a class="nav-link <?php if($page === 'about') echo 'active'; ?>" href="<?php echo BASE_URL; ?>about">About</a></li>
                    <li class="nav-item"><a class="nav-link <?php if($page === 'contact') echo 'active'; ?>" href="<?php echo BASE_URL; ?>contact">Contact</a></li>
                    <li class="nav-item"><a class="nav-link <?php if($page === 'downloads') echo 'active'; ?>" href="<?php echo BASE_URL; ?>downloads">Downloads</a></li>
                </ul>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                     <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if (in_array('Admin', $_SESSION['roles'])): ?>
                            <li class="nav-item"><a class="nav-link <?php if($page === 'admin') echo 'active'; ?>" href="<?php echo BASE_URL; ?>admin"><i class="fas fa-user-shield me-1"></i>Admin</a></li>
                        <?php endif; ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                               <i class="fas fa-user-circle me-1"></i><?php echo htmlspecialchars($_SESSION['display_name']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>profile"><i class="fas fa-user-cog fa-fw me-2"></i>My Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>logout"><i class="fas fa-sign-out-alt fa-fw me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link <?php if($page === 'register') echo 'active'; ?>" href="<?php echo BASE_URL; ?>register">Register</a></li>
                        <li class="nav-item"><a class="nav-link <?php if($page === 'login') echo 'active'; ?>" href="<?php echo BASE_URL; ?>login">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-shrink-0 py-5">
        <div class="container">
            <?php include $page_path; ?>
        </div>
    </main>

    <footer class="mt-auto py-5 bg-dark">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0"><h5 class="text-warning">Nerdy Gamer Tools</h5><p class="text-secondary">"Bringing you what no one asked for"</p><p class="text-secondary small">&copy; <?php echo date('Y'); ?> All Rights Reserved.</p></div>
                <div class="col-md-4 mb-4 mb-md-0"><h5>Quick Links</h5><ul class="list-unstyled"><li><a href="<?php echo BASE_URL; ?>news" class="link-secondary text-decoration-none">News</a></li><li><a href="<?php echo BASE_URL; ?>projects" class="link-secondary text-decoration-none">Projects</a></li><li><a href="<?php echo BASE_URL; ?>contact" class="link-secondary text-decoration-none">Contact</a></li></ul></div>
                <div class="col-md-4"><h5>Connect</h5><div class="d-flex gap-3"><a href="#" class="footer-social-link"><i class="fab fa-twitter"></i></a><a href="#" class="footer-social-link"><i class="fab fa-github"></i></a><a href="#" class="footer-social-link"><i class="fab fa-discord"></i></a></div></div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script type="module">
        // Add your Firebase project configuration here later
    </script>
</body>
</html>
