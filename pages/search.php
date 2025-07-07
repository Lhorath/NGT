<?php
// Get the search query from the URL, trimming whitespace
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$search_results = [];

// Only perform a search if the query is not empty
if (!empty($search_query)) {
    // Use a prepared statement to prevent SQL injection
    $search_term = "%" . $search_query . "%";
    $stmt = $conn->prepare("SELECT id, title, author, body, publish_date
                            FROM news
                            WHERE title LIKE ? OR body LIKE ?
                            ORDER BY publish_date DESC");
    $stmt->bind_param("ss", $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $search_results[] = $row;
    }
    $stmt->close();
}
?>

<div class="text-center mb-5">
    <h2 class="display-5">Search Results</h2>
    <?php if (!empty($search_query)): ?>
        <p class="lead text-muted">Showing results for: <strong>"<?php echo htmlspecialchars($search_query); ?>"</strong></p>
    <?php endif; ?>
</div>

<?php if (!empty($search_query) && count($search_results) > 0): ?>
    <?php foreach ($search_results as $row):
        $snippet = substr(strip_tags($row['body']), 0, 300);
    ?>
        <div class="card mb-4">
            <div class="card-body">
                <h3 class="card-title h4"><a href="<?php echo BASE_URL; ?>article?id=<?php echo $row['id']; ?>" class="text-decoration-none text-light"><?php echo htmlspecialchars($row['title']); ?></a></h3>
                <h6 class="card-subtitle mb-2 text-muted">
                    Posted by <strong><?php echo htmlspecialchars($row['author']); ?></strong> on <?php echo date('F j, Y', strtotime($row['publish_date'])); ?>
                </h6>
                <p class="card-text text-secondary"><?php echo htmlspecialchars($snippet); ?>...</p>
                <a href="<?php echo BASE_URL; ?>article?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-warning">Read More <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    <?php endforeach; ?>
<?php elseif (!empty($search_query)): ?>
    <div class="alert alert-info text-center">
        No articles were found matching your search term.
    </div>
<?php else: ?>
    <div class="alert alert-secondary text-center">
        Please enter a search term in the box above.
    </div>
<?php endif; ?>
