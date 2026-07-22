<?php
require_once __DIR__ . "/../../includes/auth.php";
require_login();

$departmentId = (int) ($_GET['id'] ?? 0);

if ($departmentId <= 0) {
    redirect_to('pages/departments/index.php?error=invalid');
}

$departmentStmt = $conn->prepare(
    "SELECT d.department_id, d.department_name, d.description, d.created_at, COUNT(e.employee_id) AS employees
     FROM departments d
     LEFT JOIN employees e ON e.department_id = d.department_id
     WHERE d.department_id = ?
     GROUP BY d.department_id, d.department_name, d.description, d.created_at"
);
$departmentStmt->bind_param('i', $departmentId);
$departmentStmt->execute();
$department = $departmentStmt->get_result()->fetch_assoc();

if (!$department) {
    redirect_to('pages/departments/index.php?error=not_found');
}

$employeesStmt = $conn->prepare(
    "SELECT employee_id, first_name, last_name, employee_code, position, email, status
     FROM employees
     WHERE department_id = ?
     ORDER BY first_name, last_name"
);
$employeesStmt->bind_param('i', $departmentId);
$employeesStmt->execute();
$employees = $employeesStmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($department['department_name']) ?> - Department</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css?v=department-details">
</head>
<body <?= app_body_attributes() ?>>
    <div class="d-flex min-vh-100">
        <?php require_once __DIR__ . "/../../includes/sidebar.php"; ?>

        <div class="flex-grow-1 d-flex flex-column min-w-0">
            <?php require_once __DIR__ . "/../../includes/navbar.php"; ?>

            <main class="flex-grow-1 p-4">
                <div class="container-fluid">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                        <div>
                            <a href="index.php" class="btn btn-outline-secondary btn-sm mb-3">
                                <i class="bi bi-arrow-left"></i> Back to Departments
                            </a>
                            <h2 class="fw-bold mb-1"><?= htmlspecialchars($department['department_name']) ?></h2>
                            <p class="text-muted mb-0">Department details and employee directory.</p>
                        </div>
                        <a href="edit.php?id=<?= (int) $department['department_id'] ?>" class="btn btn-primary">
                            <i class="bi bi-pencil-square"></i> Edit Department
                        </a>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-lg-8">
                            <div class="card shadow-sm h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="bg-primary-subtle text-primary rounded p-3"><i class="bi bi-building fs-4"></i></div>
                                        <div><h5 class="fw-bold mb-0">Department information</h5><small class="text-muted">Active department</small></div>
                                    </div>
                                    <h6 class="text-muted">Description</h6>
                                    <p class="mb-0 department-detail-description"><?= nl2br(htmlspecialchars($department['description'] ?: 'No description added.')) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-body p-4">
                                    <span class="text-muted small">TOTAL EMPLOYEES</span>
                                    <div class="display-6 fw-bold mt-1"><?= (int) $department['employees'] ?></div>
                                    <hr>
                                    <span class="text-muted small">CREATED</span>
                                    <div class="fw-semibold mt-1"><?= !empty($department['created_at']) ? date('F d, Y', strtotime($department['created_at'])) : 'Not available' ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header bg-transparent border-bottom p-4 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0"><i class="bi bi-people me-2"></i>Employees</h5>
                            <span class="badge text-bg-primary"><?= (int) $department['employees'] ?></span>
                        </div>
                        <?php if (empty($employees)): ?>
                            <div class="card-body text-center py-5 text-muted">
                                <i class="bi bi-people fs-2 d-block mb-2"></i>No employees assigned to this department.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead><tr><th class="ps-4">Employee</th><th>Position</th><th>Status</th><th class="text-end pe-4">Action</th></tr></thead>
                                    <tbody>
                                    <?php foreach ($employees as $employee): ?>
                                        <tr>
                                            <td class="ps-4"><div class="fw-semibold"><?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?></div><small class="text-muted"><?= htmlspecialchars($employee['email']) ?></small></td>
                                            <td><?= htmlspecialchars($employee['position']) ?></td>
                                            <td><span class="badge bg-success-subtle text-success"><?= htmlspecialchars($employee['status']) ?></span></td>
                                            <td class="text-end pe-4"><a class="btn btn-outline-primary btn-sm" href="../employees/view.php?id=<?= (int) $employee['employee_id'] ?>"><i class="bi bi-eye"></i> View</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="../../assets/js/submit-loading.js"></script>
</body>
</html>
