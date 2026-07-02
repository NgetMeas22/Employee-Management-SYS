<?php
require_once __DIR__ . "/../../includes/auth.php";
require_login();

$departmentId = (int) ($_GET['id'] ?? 0);

if ($departmentId <= 0) {
    redirect_to('pages/departments/index.php?error=invalid');
}

$stmt = $conn->prepare("SELECT department_id, department_name, description FROM departments WHERE department_id = ?");
$stmt->bind_param('i', $departmentId);
$stmt->execute();
$department = $stmt->get_result()->fetch_assoc();

if (!$department) {
    redirect_to('pages/departments/index.php?error=not_found');
}

$errors = [
    'invalid' => 'Department name is required.',
    'duplicate' => 'A department with this name already exists.',
    'save_failed' => 'Department could not be updated.',
];
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
                            <?php if (isset($_GET['error'], $errors[$_GET['error']])): ?>
                                <div class="alert alert-danger">
                                    <?= htmlspecialchars($errors[$_GET['error']]) ?>
                                </div>
                            <?php endif; ?>

                            <form action="../../ajax/department.php" method="POST">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="department_id" value="<?= htmlspecialchars((string) $department['department_id']) ?>">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Department Name</label>
                                        <input type="text" name="department_name" class="form-control" value="<?= htmlspecialchars($department['department_name']) ?>" required>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control" rows="3" placeholder="Short description"><?= htmlspecialchars($department['description'] ?? '') ?></textarea>
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
