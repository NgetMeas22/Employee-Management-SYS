<nav class="navbar navbar-expand-lg bg-white border-bottom px-4 py-3">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <div class="input-group search-box w-100" style="max-width:500px;">
    <span class="input-group-text bg-white border-end-0 rounded-start-pill">
        <i class="bi bi-search"></i>
    </span>

    <input
        type="text"
        class="form-control border-start-0 rounded-end-pill"
        placeholder="Search employees..."
    >
</div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-bell fs-5"></i>
            <i class="bi bi-envelope fs-5"></i>
            <a href="<?= app_base_url() ?>pages/profile/index.php">
                <img src="<?= app_base_url() ?>assets/images/profile.jpg" class="rounded-circle" alt="Admin" width="40" height="40" style="object-fit: cover;">
            </a>
            <span class="fw-semibold"><?= htmlspecialchars(current_user_name()) ?></span>
            <a href="<?= app_base_url() ?>logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>
