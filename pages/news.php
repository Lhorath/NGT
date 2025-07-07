<div class="text-center mb-5">
    <h2 class="display-5">Latest News & Updates</h2>
    <p class="lead text-muted">Stay up to date with the latest happenings.</p>
</div>

<div class="row">
    <?php
    // --- 1. PAGINATION & DATA FETCHING ---
    $items_per_page = 6; // Show 6 articles for a 3x2 grid layout
    $current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
    if ($current_page < 1) { $current_page = 1; }

    $total_articles_result = $conn->query("SELECT COUNT(id) AS total FROM news");
    $total_articles = $total_articles_result->fetch_assoc()['total'];
    $total_pages = ceil($total_articles / $items_per_page);

    if ($current_page > $total_pages && $total_pages > 0) {
        header('Location: ' . BASE_URL . 'news?p=' . $total_pages);
        exit();
    }

    $offset = ($current_page - 1) * $items_per_page;
    // UPDATED query to fetch the image_url
    $stmt = $conn->prepare("SELECT id, title, author, body, publish_date, image_url FROM news ORDER BY publish_date DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $items_per_page, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    // --- 2. DISPLAY ARTICLES ---
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $snippet = substr(strip_tags($row['body']), 0, 100);
    ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <?php if (!empty($row['image_url'])): ?>
                        <a href="<?php echo BASE_URL; ?>article?id=<?php echo $row['id']; ?>">
                            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" class="card-img-top news-card-img" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        </a>
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><a href="<?php echo BASE_URL; ?>article?id=<?php echo $row['id']; ?>" class="text-decoration-none text-light"><?php echo htmlspecialchars($row['title']); ?></a></h5>
                        <p class="card-text text-secondary small flex-grow-1"><?php echo htmlspecialchars($snippet); ?>...</p>
                        <a href="<?php echo BASE_URL; ?>article?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-warning mt-auto align-self-start">Read More</a>
                    </div>
                    <div class="card-footer text-muted small">
                        By <?php echo htmlspecialchars($row['author']); ?> on <?php echo date('M j, Y', strtotime($row['publish_date'])); ?>
                    </div>
                </div>
            </div>
    <?php
        }
    } else {
        echo '<div class="col"><div class="alert alert-info">No news articles have been posted yet.</div></div>';
    }
    $stmt->close();
    ?>
</div>

<?php
// --- 3. DISPLAY PAGINATION LINKS (No changes to this logic) ---
if ($total_pages > 1):
?>
<nav aria-label="News pagination" class="mt-4">
    <ul class="pagination justify-content-center">
        <li class="page-item <?php if ($current_page <= 1) echo 'disabled'; ?>"><a class="page-link" href="<?php echo BASE_URL; ?>news?p=<?php echo $current_page - 1; ?>">Prev</a></li>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php if ($i == $current_page) echo 'active'; ?>"><a class="page-link" href="<?php echo BASE_URL; ?>news?p=<?php echo $i; ?>"><?php echo $i; ?></a></li>
        <?php endfor; ?>
        <li class="page-item <?php if ($current_page >= $total_pages) echo 'disabled'; ?>"><a class="page-link" href="<?php echo BASE_URL; ?>news?p=<?php echo $current_page + 1; ?>">Next</a></li>
    </ul>
</nav>
<?php endif; ?>
