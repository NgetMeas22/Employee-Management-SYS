<?php
require_once __DIR__ . "/../../includes/auth.php";
require_login();

$employee = null;
$department = null;
$message = null;

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $conn->prepare("SELECT e.*, d.department_name FROM employees e LEFT JOIN departments d ON e.department_id = d.department_id WHERE e.employee_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $employee = $result->fetch_assoc();
    
    if (!$employee) {
        $message = ['error', 'Employee not found.'];
    }
} else {
    $message = ['error', 'Invalid employee ID.'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $employee ? htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) : 'Employee' ?> - View</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css?v=appearance-theme">
    <link rel="shortcut icon" href="../../assets/images/profile.jpg" type="image/jpeg">
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        .employee-header {
            background: linear-gradient(135deg, #a7a7a7bd 0%, #2b3b52c2 100%);
            color: white;
            padding: 2rem 0;
        }
        .avatar-large {
            width: 150px;
            height: 150px;
            border-radius: 12px;
            border: 4px solid white;
            object-fit: cover;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .info-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .info-label {
            color: #6c757d;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        .info-value {
            color: #212529;
            font-size: 1rem;
            font-weight: 500;
        }
        .status-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-on-leave {
            background-color: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body <?= app_body_attributes() ?>>
    <div class="d-flex min-vh-100">
        <?php require_once __DIR__ . "/../../includes/sidebar.php"; ?>

        <div class="flex-grow-1 d-flex flex-column">
            <?php require_once __DIR__ . "/../../includes/navbar.php"; ?>

            <main class="flex-grow-1 p-4">
                <?php if ($message): ?>
                    <div class="alert alert-<?= $message[0] ?> alert-dismissible fade show" role="alert">
                        <i class="bi bi-<?= $message[0] === 'error' ? 'exclamation-circle' : 'check-circle' ?> me-2"></i>
                        <?= $message[1] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <div class="text-center mt-5">
                        <a href="index.php" class="btn btn-primary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Employees
                        </a>
                    </div>
                <?php else: ?>
                    <div class="container-fluid">
                        <!-- Header with back button -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <a href="index.php" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-arrow-left"></i> Back
                                </a>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="edit.php?id=<?= $employee['employee_id'] ?>" class="btn btn-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <a href="delete.php?id=<?= $employee['employee_id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </div>
                        </div>

                        <!-- Employee Header -->
                        <div class="employee-header rounded-4 mb-4">
                            <div class="container-fluid">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-4 py-2 px-5">
                                 
                                    <div>
                                        <h1 class="fw-bold mb-2"><?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?></h1>
                                        <p class="mb-2 fs-5"><?= htmlspecialchars($employee['position'] ?? 'N/A') ?></p>
                                        <p class="mb-3 opacity-75"><?= htmlspecialchars($employee['department_name'] ?? 'No Department') ?></p>
                                        <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $employee['status'])) ?>">
                                            <i class="bi bi-circle-fill me-1"></i>
                                            <?= htmlspecialchars($employee['status']) ?>
                                        </span>
                                    </div>
                                       <div>
                                        <?php if (!empty($employee['photo'])): ?>
                                            <img src="<?= app_base_url() . htmlspecialchars($employee['photo']) ?>" alt="<?= htmlspecialchars($employee['first_name']) ?>" class="avatar-large">
                                        <?php else: ?>
                                            <div class="avatar-large d-flex align-items-center justify-content-center" style="background: rgba(255, 255, 255, 0.2);">
                                                <i class="bi bi-person-fill" style="font-size: 4rem;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4">
                            <!-- Contact Information -->
                            <div class="col-lg-6">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-white border-bottom p-4">
                                        <h5 class="fw-bold mb-0">
                                            <i class="bi bi-envelope me-2"></i>Contact Information
                                        </h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="info-card">
                                            <div class="info-label">Email Address</div>
                                            <div class="info-value">
                                                <a href="mailto:<?= htmlspecialchars($employee['email']) ?>" class="text-decoration-none">
                                                    <?= htmlspecialchars($employee['email']) ?>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="info-card">
                                            <div class="info-label">Phone Number</div>
                                            <div class="info-value">
                                                <?php if (!empty($employee['phone'])): ?>
                                                    <a href="tel:<?= htmlspecialchars($employee['phone']) ?>" class="text-decoration-none">
                                                        <?= htmlspecialchars($employee['phone']) ?>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">Not provided</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="info-card">
                                            <div class="info-label">Address</div>
                                            <div class="info-value">
                                                <?= !empty($employee['address']) ? htmlspecialchars($employee['address']) : '<span class="text-muted">Not provided</span>' ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Employment Information -->
                            <div class="col-lg-6">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-white border-bottom p-4">
                                        <h5 class="fw-bold mb-0">
                                            <i class="bi bi-briefcase me-2"></i>Employment Information
                                        </h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="info-card">
                                            <div class="info-label">Employee ID</div>
                                            <div class="info-value">#<?= (int) $employee['employee_id'] ?></div>
                                        </div>
                                        <div class="info-card">
                                            <div class="info-label">Position</div>
                                            <div class="info-value"><?= htmlspecialchars($employee['position'] ?? 'N/A') ?></div>
                                        </div>
                                        <div class="info-card">
                                            <div class="info-label">Department</div>
                                            <div class="info-value"><?= htmlspecialchars($employee['department_name'] ?? 'No Department') ?></div>
                                        </div>
                                        <div class="info-card">
                                            <div class="info-label">Hire Date</div>
                                            <div class="info-value">
                                                <?php if (!empty($employee['hire_date'])): ?>
                                                    <?= date('F d, Y', strtotime($employee['hire_date'])) ?>
                                                    <small class="d-block text-muted mt-1">
                                                        <?php 
                                                        $hire_date = new DateTime($employee['hire_date']);
                                                        $today = new DateTime();
                                                        $interval = $today->diff($hire_date);
                                                        echo '(' . $interval->y . ' years, ' . $interval->m . ' months)';
                                                        ?>
                                                    </small>
                                                <?php else: ?>
                                                    <span class="text-muted">Not provided</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="info-card">
                                            <div class="info-label">Employment Status</div>
                                            <div class="info-value">
                                                <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $employee['status'])) ?>">
                                                    <?= htmlspecialchars($employee['status']) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="row g-4 mt-2">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <a href="index.php" class="btn btn-outline-secondary">
                                        <i class="bi bi-list"></i> View All Employees
                                    </a>
                                    <a href="edit.php?id=<?= $employee['employee_id'] ?>" class="btn btn-primary">
                                        <i class="bi bi-pencil"></i> Edit Employee
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/submit-loading.js"></script>
</body>
</html>
