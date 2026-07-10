<?php
require_once __DIR__ . "/../../includes/auth.php";
require_login();

// Fetch real employees from DB
$employees = [];
$sql = "SELECT e.employee_id, e.first_name, e.last_name, e.position, COALESCE(d.department_name, '') AS department, e.status, e.email, e.photo
        FROM employees e
        LEFT JOIN departments d ON e.department_id = d.department_id
        ORDER BY e.employee_id DESC";

$res = $conn->query($sql);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $employees[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees</title>
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
                            <h2 class="fw-bold mb-1">Employees</h2>
                            <p class="text-muted mb-0">View, add, and manage employee records.</p>
                        </div>
                        <a href="create.php" class="btn btn-primary d-inline-flex align-items-center gap-2">
                            <i class="bi bi-plus-lg"></i>
                            Add New Employee
                        </a>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Employee</th>
                                            <th>Position</th>
                                            <th>Department</th>
                                            <th>Status</th>
                                            <th>Profile</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($employees as $employee): ?>
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold"><?= htmlspecialchars(($employee['first_name'] ?? '') . ' ' . ($employee['last_name'] ?? '')) ?></div>
                                                    <small class="text-muted"><?= htmlspecialchars($employee['email'] ?? '') ?></small>
                                                </td>
                                                <td><?= htmlspecialchars($employee['position'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($employee['department'] ?? '') ?></td>
                                                <td>
                                                    <span class="badge <?= ($employee['status'] ?? '') === 'Active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' ?>">
                                                        <?= htmlspecialchars($employee['status'] ?? '') ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <?php $imagePath = !empty($employee['photo']) ? app_base_url() . htmlspecialchars($employee['photo']) : app_base_url() . 'assets/images/profile.jpg'; ?>
                                                    <img src="<?= $imagePath ?>" alt="Employee photo" class=" border-dark-subtle border-2 border rounded-3" width="75" height="75" style="object-fit: cover;">
                                                </td> 
                                                <td class="text-end">
                                                    <a href="view.php?id=<?= (int) $employee['employee_id'] ?>" class="btn btn-sm btn-outline-success">
                                                        <i class="bi bi-eye"></i>
                                                        View Detail
                                                    </a>
                                                    <a href="edit.php?id=<?= (int) $employee['employee_id'] ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-pencil-square"></i>
                                                        Edit
                                                    </a>
                                                    <form action="delete.php" method="POST" class="d-inline-block ms-2" onsubmit="return confirm('Delete this employee?');">
                                                        <input type="hidden" name="id" value="<?= (int) $employee['employee_id'] ?>">
                                                        <input type="hidden" name="image" value="<?= htmlspecialchars($employee['photo'] ?? '') ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                        
                                                    </form>
                                                    
                                                </td>
                                            </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </main>
        </div>
    </div>
<script src="../../assets/js/submit-loading.js"></script>
</body>
</html>
