<?php
require_once __DIR__ . "/../../includes/auth.php";
require_login();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Department</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="d-flex min-vh-100">
        <?php require_once __DIR__ . "/../../includes/sidebar.php"; ?>

        <div class="flex-grow-1 d-flex flex-column">
            <?php require_once __DIR__ . "/../../includes/navbar.php"; ?>

            <main class="flex-grow-1 p-4">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="fw-bold mb-1">Edit Department</h2>
                            <p class="text-muted mb-0">Update department details.</p>
                        </div>
                        <a href="index.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i>
                            Back
                        </a>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <form action="index.php" method="POST">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Department Name</label>
                                        <input type="text" class="form-control" value="Engineering" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Manager</label>
                                        <input type="text" class="form-control" value="David Miller" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Employee Count</label>
                                        <input type="number" class="form-control" value="82" min="0">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Status</label>
                                        <select class="form-select">
                                            <option selected>Active</option>
                                            <option>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i>
                                        Update Department
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
