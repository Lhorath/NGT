<!-- Hero Section -->
<div class="text-center bg-body-tertiary p-5 rounded-3 mb-5">
    <h1 class="display-4 text-warning">Welcome to Nerdy Gamer Tools</h1>
    <p class="lead text-muted">Your one-stop hub for niche projects, utilities, and downloads that you never knew you needed. Explore the latest updates and dive into our collection of tools.</p>
    <hr class="my-4">
    <p>Check out our latest projects and see what we've been working on.</p>
    <a class="btn btn-warning btn-lg" href="<?php echo BASE_URL; ?>projects" role="button">
        <i class="fas fa-gamepad me-2"></i>View Projects
    </a>
</div>

<!-- Three-Column Features Section -->
<div class="row text-center">

    <!-- Card 1: Projects -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-body d-flex flex-column">
                <div class="mb-3"><i class="fas fa-flask-vial fa-3x text-warning"></i></div>
                <h3 class="card-title h4">Our Projects</h3>
                <p class="card-text text-secondary">Browse our collection of unique web apps and utilities hosted right here on the site.</p>
                <div class="mt-auto">
                    <a href="<?php echo BASE_URL; ?>projects" class="btn btn-outline-warning">Explore Projects</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Latest News -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-body d-flex flex-column">
                <div class="mb-3"><i class="fas fa-newspaper fa-3x text-warning"></i></div>
                <h3 class="card-title h4">Latest News</h3>
                <?php
                    // Fetch the single latest news article to display in the card
                    $sql = "SELECT id, title, body FROM news ORDER BY publish_date DESC LIMIT 1";
                    $result = $conn->query($sql);
                    if ($result && $result->num_rows > 0) {
                        $latest_post = $result->fetch_assoc();
                        $snippet = substr(strip_tags($latest_post['body']), 0, 80);
                        echo '<p class="text-secondary"><strong>' . htmlspecialchars($latest_post['title']) . '</strong><br>' . htmlspecialchars($snippet) . '...</p>';
                        $news_link = BASE_URL . 'article?id=' . $latest_post['id'];
                    } else {
                        echo '<p class="text-secondary">No recent news. Check back soon for updates!</p>';
                        $news_link = BASE_URL . 'news';
                    }
                ?>
                <div class="mt-auto">
                    <a href="<?php echo $news_link; ?>" class="btn btn-outline-warning">Read More</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Join the Community -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-body d-flex flex-column">
                <div class="mb-3"><i class="fas fa-users fa-3x text-warning"></i></div>
                <h3 class="card-title h4">Join Us</h3>
                <p class="card-text text-secondary">Create an account to join the community, comment on articles, and get access to more features.</p>
                <div class="mt-auto">
                    <a href="<?php echo BASE_URL; ?>register" class="btn btn-outline-warning">Register Now</a>
                </div>
            </div>
        </div>
    </div>

</div>
