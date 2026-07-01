<?php
require_once __DIR__ . "/../../includes/auth.php";
require_login();

$departments = [
    ['name' => 'Engineering', 'manager' => 'David Miller', 'employees' => 82, 'status' => 'Active'],
    ['name' => 'Human Resources', 'manager' => 'Emily Thompson', 'employees' => 18, 'status' => 'Active'],
    ['name' => 'Marketing', 'manager' => 'James Peterson', 'employees' => 44, 'status' => 'Active'],
    ['name' => 'Operations', 'manager' => 'Robert Wilson', 'employees' => 61, 'status' => 'Active'],
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
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
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

                    <div class="row g-4">
                        <?php foreach ($departments as $department): ?>
                            <div class="col-md-6 col-xl-3">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="bg-primary-subtle text-primary rounded p-2">
                                                <i class="bi bi-building"></i>
                                            </div>
                                            <span class="badge bg-success-subtle text-success"><?= htmlspecialchars($department['status']) ?></span>
                                        </div>

                                        <h5 class="fw-bold mb-1"><?= htmlspecialchars($department['name']) ?></h5>
                                        <p class="text-muted mb-3">Manager: <?= htmlspecialchars($department['manager']) ?></p>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted small">Employees</span>
                                            <span class="fw-bold"><?= htmlspecialchars((string) $department['employees']) ?></span>
                                        </div>

                                        <a href="edit.php" class="btn btn-outline-primary btn-sm w-100 mt-3">
                                            <i class="bi bi-pencil-square"></i>
                                            Edit Department
                                        </a>
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
