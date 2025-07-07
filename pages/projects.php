<div class="text-center mb-5">
    <h2 class="display-5">My Projects</h2>
    <p class="lead text-muted">A collection of my web applications and utilities.</p>
</div>

<div class="row">
    <?php
        $projectsDirectory = 'the_files';

        if (is_dir($projectsDirectory)) {
            $projectFolders = glob($projectsDirectory . '/*', GLOB_ONLYDIR);

            if (count($projectFolders) > 0) {
                foreach ($projectFolders as $folder) {
                    $projectName = basename($folder);
                    $formattedProjectName = ucwords(str_replace('-', ' ', $projectName));
                    $projectUrl = BASE_URL . $folder;

                    // --- NEW: LOGIC TO READ METADATA ---

                    // 1. Set default values for icon and description
                    $projectIcon = 'fa-folder-open'; // Default icon
                    $projectDescription = 'No description provided.'; // Default description

                    // 2. Check if a meta.json file exists in the folder
                    $meta_file_path = $folder . '/meta.json';
                    if (file_exists($meta_file_path)) {
                        // 3. Read the file and decode the JSON content
                        $meta_content = file_get_contents($meta_file_path);
                        $meta_data = json_decode($meta_content, true);

                        // 4. If the JSON is valid, use the data from it
                        if (is_array($meta_data)) {
                            // Use the description from the file, or keep the default
                            $projectDescription = $meta_data['description'] ?? $projectDescription;
                            // Use the icon from the file, or keep the default
                            $projectIcon = $meta_data['icon'] ?? $projectIcon;
                        }
                    }
    ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 text-center">
                            <div class="card-body d-flex flex-column">
                                <div class="mb-3"><i class="fas <?php echo htmlspecialchars($projectIcon); ?> fa-3x text-warning"></i></div>

                                <h5 class="card-title"><?php echo htmlspecialchars($formattedProjectName); ?></h5>

                                <p class="card-text text-secondary flex-grow-1"><?php echo htmlspecialchars($projectDescription); ?></p>

                                <a href="<?php echo htmlspecialchars($projectUrl); ?>" class="btn btn-warning mt-auto" target="_blank">
                                    <i class="fas fa-external-link-alt me-2"></i>View Project
                                </a>
                            </div>
                        </div>
                    </div>
    <?php
                }
            } else {
                echo '<div class="col"><div class="alert alert-info">No project folders found in the "' . htmlspecialchars($projectsDirectory) . '" directory.</div></div>';
            }
        } else {
            echo '<div class="col"><div class="alert alert-danger">Error: Directory not found: ' . htmlspecialchars($projectsDirectory) . '</div></div>';
        }
    ?>
</div>
