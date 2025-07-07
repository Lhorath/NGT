<div class="row justify-content-center">
    <div class="col-md-8 text-center">
        <div class="card bg-dark-subtle border-secondary-subtle">
            <div class="card-body p-5">
                <i class="fas fa-compass-slash fa-5x text-warning mb-4"></i>
                <h1 class="display-4 fw-bold">404 - Lost in the Exiled Lands</h1>
                <p class="lead text-muted my-4">
                    By Crom! It seems you've wandered off the beaten path. The page you seek is either hidden by sorcery or was never here to begin with.
                </p>

                <p class="text-secondary">Try searching for what you need:</p>

                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <form action="<?php echo BASE_URL; ?>" method="GET">
                            <input type="hidden" name="page" value="search">
                            <div class="input-group mb-4">
                                <input type="text" name="q" class="form-control" placeholder="Search news and articles..." required>
                                <button class="btn btn-warning" type="submit">Search</button>
                            </div>
                        </form>
                    </div>
                </div>

                <a href="<?php echo BASE_URL; ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-home me-2"></i>Return to Homepage
                </a>
                <a href="<?php echo BASE_URL; ?>contact" class="btn btn-outline-secondary btn-lg ms-2">
                    <i class="fas fa-envelope me-2"></i>Report a Broken Link
                </a>
            </div>
        </div>
    </div>
</div>
