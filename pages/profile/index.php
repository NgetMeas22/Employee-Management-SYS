<?php
require_once __DIR__ . "/../../includes/auth.php";
require_login();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css?v=appearance-theme">
</head>
<body <?= app_body_attributes() ?>>
    <div class="d-flex min-vh-100">
        <?php require_once __DIR__ . "/../../includes/sidebar.php"; ?>

        <div class="flex-grow-1 d-flex flex-column">
            <?php require_once __DIR__ . "/../../includes/navbar.php"; ?>

            <main class="flex-grow-1 p-4">
                <div class="container-fluid">
                    <div class="mb-4">
                        <h2 class="fw-bold mb-1">Profile</h2>
                        <p class="text-muted mb-0">Manage your account information.</p>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <img src="../../assets/images/profile.jpg" class="rounded-circle" width="72" height="72" alt="Profile" style="object-fit: cover;">
                                <div>
                                    <h4 class="fw-bold mb-1"><?= htmlspecialchars(current_user_name()) ?></h4>
                                    <p class="text-muted mb-0">System Administrator</p>
                                </div>
                            </div>

                            <form>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Username</label>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars(current_user_name()) ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Role</label>
                                        <input type="text" class="form-control" value="Admin">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="button" class="btn btn-primary">
                                        <i class="bi bi-save"></i>
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
