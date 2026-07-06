<?php
require_once __DIR__ . "/../../includes/auth.php";
require_login();

$id = (int) ($_GET['id'] ?? 0);
$employee = null;
if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM employees WHERE employee_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $employee = $stmt->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
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
                            <h2 class="fw-bold mb-1">Edit Employee</h2>
                            <p class="text-muted mb-0">Update employee information.</p>
                        </div>
                        <a href="index.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i>
                            Back
                        </a>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <?php $departments = $conn->query("SELECT department_id, department_name FROM departments ORDER BY department_name ASC"); ?>
                            <form action="../../ajax/update_employee.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="employee_id" value="<?= (int) ($employee['employee_id'] ?? 0) ?>">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">First Name</label>
                                        <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($employee['first_name'] ?? '') ?>" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($employee['last_name'] ?? '') ?>" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($employee['email'] ?? '') ?>" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($employee['phone'] ?? '') ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Position</label>
                                        <input type="text" name="position" class="form-control" value="<?= htmlspecialchars($employee['position'] ?? '') ?>" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Department</label>
                                        <select name="department_id" class="form-select" required>
                                            <?php if ($departments && $departments->num_rows > 0): ?>
                                                <option value="" disabled>Select department</option>
                                                <?php while ($d = $departments->fetch_assoc()): ?>
                                                    <option value="<?= (int) $d['department_id'] ?>" <?= (int) $d['department_id'] === (int) ($employee['department_id'] ?? 0) ? 'selected' : '' ?>><?= htmlspecialchars($d['department_name']) ?></option>
                                                <?php endwhile; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select" required>
                                            <option value="Active" <?= ($employee['status'] ?? '') === 'Active' ? 'selected' : '' ?>>Active</option>
                                            <option value="On Leave" <?= ($employee['status'] ?? '') === 'On Leave' ? 'selected' : '' ?>>On Leave</option>
                                            <option value="Inactive" <?= ($employee['status'] ?? '') === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Address</label>
                                        <textarea name="address" class="form-control" rows="3"><?= htmlspecialchars($employee['address'] ?? '') ?></textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Photo</label>
                                        <input type="file" name="avatar" accept="image/*" class="form-control">
                                        <?php if (!empty($employee['photo'])): ?>
                                            <small class="d-block mt-1">Current: <?= htmlspecialchars($employee['photo']) ?></small>
                                        <?php endif; ?>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Hire Date</label>
                                        <input type="date" name="hire_date" class="form-control" value="<?= htmlspecialchars($employee['hire_date'] ?? '') ?>">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i>
                                        Update Employee
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
