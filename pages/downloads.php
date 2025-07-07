<div class="text-center mb-5">
    <h2 class="display-5 fw-bold">Project Downloads</h2>
    <p class="lead text-muted mb-5">Download the latest releases of our projects.</p>
</div>

<?php
// --- 1. FETCH ALL DOWNLOADS FROM THE DATABASE ---
$downloads_by_project = [];
$sql = "SELECT id, project_name, version, description, file_path, file_size, download_count, upload_date
        FROM downloads
        ORDER BY project_name ASC, upload_date DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Group the downloads by their project name
    while($row = $result->fetch_assoc()) {
        $downloads_by_project[$row['project_name']][] = $row;
    }
}

// --- 2. DISPLAY THE DOWNLOADS ---
if (!empty($downloads_by_project)):
?>
    <div class="accordion" id="downloadsAccordion">
        <?php
        $i = 0;
        foreach ($downloads_by_project as $project_name => $downloads):
            $i++;
        ?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-<?php echo $i; ?>">
                    <button class="accordion-button <?php if($i > 1) echo 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $i; ?>" aria-expanded="<?php echo $i === 1 ? 'true' : 'false'; ?>" aria-controls="collapse-<?php echo $i; ?>">
                        <i class="fas fa-archive fa-fw me-3 text-warning"></i>
                        <span class="fw-bold fs-5"><?php echo htmlspecialchars($project_name); ?></span>
                    </button>
                </h2>
                <div id="collapse-<?php echo $i; ?>" class="accordion-collapse collapse <?php if($i === 1) echo 'show'; ?>" aria-labelledby="heading-<?php echo $i; ?>" data-bs-parent="#downloadsAccordion">
                    <div class="accordion-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($downloads as $file): ?>
                                <li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-center p-3">
                                    <div>
                                        <h5 class="mb-1">Version <?php echo htmlspecialchars($file['version']); ?></h5>
                                        <?php if(!empty($file['description'])): ?>
                                            <p class="mb-1 text-muted"><?php echo nl2br(htmlspecialchars($file['description'])); ?></p>
                                        <?php endif; ?>
                                        <small class="text-secondary">
                                            Size: <?php echo round($file['file_size'] / 1048576, 2); ?> MB &bull;
                                            Downloads: <?php echo $file['download_count']; ?>
                                        </small>
                                    </div>
                                    <a href="<?php echo BASE_URL; ?>download.php?id=<?php echo $file['id']; ?>" class="btn btn-warning mt-3 mt-md-0">
                                        <i class="fas fa-download me-2"></i>Download
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="alert alert-info text-center">
        There are currently no downloads available. Please check back later.
    </div>
<?php endif; ?>
