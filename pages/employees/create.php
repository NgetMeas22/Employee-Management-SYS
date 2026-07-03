<?php
require_once __DIR__ . "/../../includes/auth.php";
require_login();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Employee</title>
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
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="fw-bold mb-1">Add New Employee</h2>
                            <p class="text-muted mb-0">Create a new employee profile.</p>
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
                                        <label class="form-label">Full Name</label>
                                        <input type="text" name="name" class="form-control" placeholder="Employee name" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="employee@example.com" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="phone" class="form-control" placeholder="Phone number">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Position</label>
                                        <input type="text" name="position" class="form-control" placeholder="Job position" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Department</label>
                                        <select name="department" class="form-select" required>
                                            <option value="">Choose department</option>
                                            <option>Engineering</option>
                                            <option>Human Resources</option>
                                            <option>Marketing</option>
                                            <option>Operations</option>
                                            <option>Finance</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select" required>
                                            <option>Active</option>
                                            <option>On Leave</option>
                                            <option>Inactive</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Address</label>
                                        <textarea name="address" class="form-control" rows="3" placeholder="Employee address"></textarea>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i>
                                        Save Employee
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
