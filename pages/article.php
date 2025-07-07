<?php
// --- 1. VALIDATE ID & FETCH ARTICLE ---
$article = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $article_id = (int)$_GET['id'];
    // UPDATED: Fetch the image_url as well
    $stmt = $conn->prepare("SELECT title, author, body, publish_date, image_url FROM news WHERE id = ?");
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $article = $result->fetch_assoc();
    }
    $stmt->close();
}

// If no article was found, show an alert and stop rendering.
if (!$article) {
    echo '<div class="alert alert-danger"><strong>Article Not Found.</strong> Sorry, the article you are looking for does not exist.</div>';
    return;
}
?>

<!-- NEW: Custom styles for the hero header -->
<style>
    .article-header {
        position: relative;
        padding: 8rem 2rem;
        border-radius: var(--bs-border-radius);
        overflow: hidden;
        background-size: cover;
        background-position: center;
        color: #fff;
    }
    .article-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6); /* Dark overlay for text readability */
        z-index: 1;
    }
    .article-header-content {
        position: relative;
        z-index: 2;
    }
</style>


<!-- NEW: Dynamic Hero Header Section -->
<!-- We use an inline style to set the background image from our database -->
<div class="article-header text-center mb-5" style="background-image: url('<?php echo htmlspecialchars($article['image_url'] ?? 'https://placehold.co/1200x400/1e1e1e/444?text=Nerdy+Gamer+Tools'); ?>');">
    <div class="article-header-content">
        <h1 class="display-4 fw-bold"><?php echo htmlspecialchars($article['title']); ?></h1>
        <p class="lead mb-0">
            By <strong><?php echo htmlspecialchars($article['author']); ?></strong>
            on <?php echo date('F j, Y', strtotime($article['publish_date'])); ?>
        </p>
    </div>
</div>


<!-- NEW: Centered column for readable article body -->
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4 p-md-5">
                <article class="news-body">
                    <?php
                        // Since the body contains trusted HTML from the admin panel, we output it directly.
                        echo $article['body'];
                    ?>
                </article>
            </div>
        </div>

        <hr class="my-5">

        <div class="text-center">
             <a href="<?php echo BASE_URL; ?>news" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back to News List</a>
        </div>
    </div>
</div>
