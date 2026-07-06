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
                            <?php
                            $departments = $conn->query("SELECT department_id, department_name FROM departments ORDER BY department_name ASC");
                            $error = $_GET['error'] ?? '';
                            $statusMessage = $_GET['status'] ?? '';
                            ?>
                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                            <?php elseif ($statusMessage === 'created'): ?>
                                <div class="alert alert-success">Employee successfully created.</div>
                            <?php endif; ?>
                            <form action="<?= app_base_url() ?>ajax/process-employee.php" method="POST" enctype="multipart/form-data">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">First Name</label>
                                        <input type="text" name="first_name" class="form-control" placeholder="First name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" name="last_name" class="form-control" placeholder="Last name" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="employee@example.com" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="phone" class="form-control" placeholder="Phone number" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Gender</label>
                                        <select name="gender" class="form-select" required>
                                            <option value="" disabled selected>Select gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Date of Birth</label>
                                        <input type="date" name="dob" class="form-control" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Salary</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0 text-muted" style="font-size: 0.875rem;">$</span>
                                            <input type="number" step="0.01" min="0" name="salary" class="form-control border-start-0 ps-1" placeholder="85000" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Position</label>
                                        <input type="text" name="position" class="form-control" placeholder="Job position" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Department</label>
                                        <select name="department_id" class="form-select" required>
                                            <?php if ($departments && $departments->num_rows > 0): ?>
                                                <option value="" disabled selected>Choose department</option>
                                                <?php while ($d = $departments->fetch_assoc()): ?>
                                                    <option value="<?= (int) $d['department_id'] ?>"><?= htmlspecialchars($d['department_name']) ?></option>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <option value="" disabled>No departments</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select" required>
                                            <option value="Active">Active</option>
                                            <option value="On Leave">On Leave</option>
                                            <option value="Inactive">Inactive</option>
                                            <option value="Resigned">Resigned</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Hire Date</label>
                                        <input type="date" name="hire_date" class="form-control" required>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Address</label>
                                        <textarea name="address" class="form-control" rows="3" placeholder="Employee address"></textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Photo</label>
                                        <input type="file" name="avatar" accept="image/*" class="form-control">
                                        <small class="text-muted">Accepted: jpg, png, gif, webp. Recommended size 400×400px.</small>
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
