<?php
require_once __DIR__ . "/../../includes/auth.php";
require_login();

$departments = [];

$result = $conn->query(
    "SELECT d.department_id, d.department_name, d.description, COUNT(e.employee_id) AS employees
     FROM departments d
     LEFT JOIN employees e ON e.department_id = d.department_id
     GROUP BY d.department_id, d.department_name, d.description
     ORDER BY d.department_name"
);

if ($result) {
    $departments = $result->fetch_all(MYSQLI_ASSOC);
}

$messages = [
    'created' => ['success', 'Department created successfully.'],
    'updated' => ['success', 'Department updated successfully.'],
    'deleted' => ['success', 'Department deleted successfully.'],
];

$errors = [
    'department_has_employees' => 'Move or delete employees in this department before deleting it.',
    'delete_failed' => 'Department could not be deleted.',
    'invalid' => 'Invalid department request.',
    'not_found' => 'Department not found.',
    'unknown_action' => 'Unknown department action.',
];
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments</title>
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
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                        <div>
                            <h2 class="fw-bold mb-1">Departments</h2>
                            <p class="text-muted mb-0">Organize employees by department and manager.</p>
                        </div>
                        <a href="create.php" class="btn btn-primary d-inline-flex align-items-center gap-2">
                            <i class="bi bi-plus-lg"></i>
                            Add Department
                        </a>
                    </div>

                    <?php if (isset($_GET['status'], $messages[$_GET['status']])): ?>
                        <div class="alert alert-<?= htmlspecialchars($messages[$_GET['status']][0]) ?>">
                            <?= htmlspecialchars($messages[$_GET['status']][1]) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['error'], $errors[$_GET['error']])): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($errors[$_GET['error']]) ?>
                        </div>
                    <?php endif; ?>

                    <div class="row g-4">
                        <?php if (empty($departments)): ?>
                            <div class="col-12">
                                <div class="card shadow-sm">
                                    <div class="card-body text-center py-5">
                                        <i class="bi bi-building text-muted fs-1"></i>
                                        <h5 class="fw-bold mt-3">No departments yet</h5>
                                        <p class="text-muted mb-3">Create your first department to start organizing employees.</p>
                                        <a href="create.php" class="btn btn-primary">
                                            <i class="bi bi-plus-lg"></i>
                                            Add Department
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php foreach ($departments as $department): ?>
                            <div class="col-md-6 col-xl-3">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="bg-primary-subtle text-primary rounded p-2">
                                                <i class="bi bi-building"></i>
                                            </div>
                                            <span class="badge bg-success-subtle text-success">Active</span>
                                        </div>

                                        <h5 class="fw-bold mb-1"><?= htmlspecialchars($department['department_name']) ?></h5>
                                        <p class="text-muted mb-3"><?= htmlspecialchars($department['description'] ?: 'No description added.') ?></p>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted small">Employees</span>
                                            <span class="fw-bold"><?= htmlspecialchars((string) $department['employees']) ?></span>
                                        </div>

                                        <div class="d-flex gap-2 mt-3">
                                            <a href="edit.php?id=<?= htmlspecialchars((string) $department['department_id']) ?>" class="btn btn-outline-primary btn-sm flex-fill">
                                                <i class="bi bi-pencil-square"></i>
                                                Edit
                                            </a>
                                            <form action="../../ajax/department.php" method="POST" class="flex-fill" onsubmit="return confirm('Delete this department?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="department_id" value="<?= htmlspecialchars((string) $department['department_id']) ?>">
                                                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                                    <i class="bi bi-trash"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
