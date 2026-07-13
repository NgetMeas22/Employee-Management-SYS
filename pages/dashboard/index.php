<?php
require_once __DIR__ . "/../../includes/auth.php";
require_login();

$totalEmployees = 0;
$totalDepartments = 0;
$activeEmployees = 0;
$newThisMonth = 0;
$inactiveEmployees = 0;
$departmentStats = [];
$recentEmployees = [];
$monthlyGrowth = [];

// Get Total Employees
$result = $conn->query("SELECT COUNT(*) AS total FROM employees");
if ($result) {
    $data = $result->fetch_assoc();
    $totalEmployees = (int) ($data['total'] ?? 0);
}

// Get Total Departments
$result = $conn->query("SELECT COUNT(*) AS total FROM departments");
if ($result) {
    $data = $result->fetch_assoc();
    $totalDepartments = (int) ($data['total'] ?? 0);
}

// Get Active Employees
$result = $conn->query("SELECT COUNT(*) AS total FROM employees WHERE status = 'Active'");
if ($result) {
    $data = $result->fetch_assoc();
    $activeEmployees = (int) ($data['total'] ?? 0);
}

// Get Inactive Employees
$result = $conn->query("SELECT COUNT(*) AS total FROM employees WHERE status = 'Inactive'");
if ($result) {
    $data = $result->fetch_assoc();
    $inactiveEmployees = (int) ($data['total'] ?? 0);
}

// Get New Employees This Month
$result = $conn->query("SELECT COUNT(*) AS total FROM employees WHERE MONTH(hire_date) = MONTH(CURDATE()) AND YEAR(hire_date) = YEAR(CURDATE())");
if ($result) {
    $data = $result->fetch_assoc();
    $newThisMonth = (int) ($data['total'] ?? 0);
}

// Get Department Distribution
$result = $conn->query("SELECT d.department_name, COUNT(e.employee_id) AS employee_count FROM departments d LEFT JOIN employees e ON d.department_id = e.department_id GROUP BY d.department_id, d.department_name ORDER BY employee_count DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $departmentStats[] = $row;
    }
}

// Get Recent Employees (last 5)
$result = $conn->query("SELECT employee_id, first_name, last_name, email, position, hire_date, status FROM employees ORDER BY hire_date DESC LIMIT 5");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recentEmployees[] = $row;
    }
}

// Get Monthly Growth for current year (last 6 months)
$currentYear = date('Y');
for ($i = 5; $i >= 0; $i--) {
    $month = date('Y-m-01', strtotime("-$i months"));
    $monthName = date('M', strtotime($month));
    $result = $conn->query("SELECT COUNT(*) AS total FROM employees WHERE YEAR(hire_date) = YEAR('$month') AND MONTH(hire_date) = MONTH('$month')");
    if ($result) {
        $data = $result->fetch_assoc();
        $monthlyGrowth[$monthName] = (int) ($data['total'] ?? 0);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css?v=appearance-theme">
    <link rel="shortcut icon" href="../../assets/images/profile.jpg" type="image/jpeg">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        .metric-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background-color: #eef2f6;
            color: #4f46e5;
        }
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: var(--app-muted);
            background-color: var(--app-bg);
        }
        .chart-container {
            position: relative;
            height: 260px;
            width: 100%;
        }
    </style>
</head>
<body <?= app_body_attributes() ?>>
    <div class="d-flex min-vh-100">
        <?php require_once __DIR__ . "/../../includes/sidebar.php"; ?>

        <div class="flex-grow-1 d-flex flex-column">
            <?php require_once __DIR__ . "/../../includes/navbar.php"; ?>

            <main class="flex-grow-1 p-4">
                <div class="container-fluid">
                    
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                        <div>
                            <h2 class="fw-bold mb-1">Welcome back, <?= htmlspecialchars(current_user_name() ?? 'Admin') ?>!</h2>
                            <p class="text-muted mb-0">Here's what's happening with your workforce today.</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="export.php" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 border-secondary-subtle text-dark bg-white">
                                <i class="bi bi-download"></i> Export Report
                            </a>
                            <a href="../employees/create.php" class="btn btn-primary d-inline-flex align-items-center gap-2 bg-gradient">
                                <i class="bi bi-plus-lg"></i> New Employee
                            </a>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-xl-3 col-md-6">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="metric-icon"><i class="bi bi-people-fill fs-5"></i></div>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle d-flex align-items-center gap-1">
                                            <i class="bi bi-graph-up"></i> <?= $newThisMonth > 0 ? '+' . $newThisMonth : '0' ?>
                                        </span>
                                    </div>
                                    <h6 class="text-muted text-uppercase small fw-bold tracking-wider mb-1">Total Employees</h6>
                                    <h2 class="fw-bold mb-0"><?= number_format($totalEmployees) ?></h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="metric-icon"><i class="bi bi-building fs-5"></i></div>
                                        <span class="badge bg-light text-secondary border d-flex align-items-center gap-1">Active</span>
                                    </div>
                                    <h6 class="text-muted text-uppercase small fw-bold tracking-wider mb-1">Total Departments</h6>
                                    <h2 class="fw-bold mb-0"><?= number_format($totalDepartments) ?></h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="metric-icon"><i class="bi bi-person-check-fill fs-5"></i></div>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle d-flex align-items-center gap-1">
                                            <i class="bi bi-check-circle"></i> <?= $totalEmployees > 0 ? round(($activeEmployees / $totalEmployees) * 100) : 0 ?>%
                                        </span>
                                    </div>
                                    <h6 class="text-muted text-uppercase small fw-bold tracking-wider mb-1">Active Employees</h6>
                                    <h2 class="fw-bold mb-0"><?= number_format($activeEmployees) ?></h2>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="metric-icon"><i class="bi bi-person-plus-fill fs-5"></i></div>
                                        <span class="badge <?= $newThisMonth > 0 ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-light text-secondary border' ?>">
                                            <?= $newThisMonth > 0 ? 'New' : 'None' ?>
                                        </span>
                                    </div>
                                    <h6 class="text-muted text-uppercase small fw-bold tracking-wider mb-1">New This Month</h6>
                                    <h2 class="fw-bold mb-0"><?= number_format($newThisMonth) ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-lg-8">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="fw-bold mb-0">Employee Growth</h5>
                                        <small class="text-muted">Hiring statistics for the current year</small>
                                    </div>
                                </div>
                                <div class="card-body px-4 pb-4">
                                    <div class="chart-container">
                                        <canvas id="growthChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white border-0 pt-4 px-4">
                                    <h5 class="fw-bold mb-0">Department Distribution</h5>
                                </div>
                                <div class="card-body px-4 pb-4 d-flex flex-column justify-content-between">
                                    <div class="chart-container mb-3" style="height: 180px;">
                                        <canvas id="deptChart"></canvas>
                                    </div>
                                    <div class="d-flex flex-column gap-2">
                                        <?php foreach (array_slice($departmentStats, 0, 4) as $index => $dept): ?>
                                            <div class="d-flex justify-content-between align-items-center small">
                                                <span>
                                                    <i class="bi bi-circle-fill me-2 fs-7" style="color: <?php 
                                                        $colors = ['#0d6efd', '#0dcaf0', '#dee2e6', '#6c757d'];
                                                        echo $colors[$index] ?? '#0d6efd';
                                                    ?>"></i><?= htmlspecialchars($dept['department_name']) ?>
                                                </span>
                                                <span class="fw-semibold"><?= $dept['employee_count'] ?> employees</span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-white border-0 pt-4 px-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="fw-bold mb-0">Recent Employees</h5>
                                            <small class="text-muted">Latest employees added to the system</small>
                                        </div>
                                        <a href="../employees/index.php" class="btn btn-sm btn-outline-primary">View Employees All</a>
                                    </div>
                                </div>
                                <div class="card-body px-4 pb-4">
                                    <?php if (!empty($recentEmployees)): ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Position</th>
                                                        <th>Email</th>
                                                        <th>Hire Date</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($recentEmployees as $emp): ?>
                                                        <tr>
                                                            <td class="fw-medium">
                                                                <?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?>
                                                            </td>
                                                            <td><?= htmlspecialchars($emp['position'] ?? 'N/A') ?></td>
                                                            <td><small><?= htmlspecialchars($emp['email']) ?></small></td>
                                                            <td><?= date('M d, Y', strtotime($emp['hire_date'])) ?></td>
                                                            <td>
                                                                <span class="badge <?= $emp['status'] === 'Active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' ?>">
                                                                    <?= htmlspecialchars($emp['status']) ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <a href="../employees/view.php?id=<?= $emp['employee_id'] ?>" class="btn btn-sm btn-outline-primary">View Detail</a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-info mb-0">
                                            <i class="bi bi-info-circle me-2"></i>No employees found. <a href="../employees/create.php">Add your first employee</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Prepare data for charts
        const growthData = <?= json_encode($monthlyGrowth) ?>;
        const departmentData = <?= json_encode(array_map(fn($d) => ['name' => $d['department_name'], 'count' => $d['employee_count']], $departmentStats)) ?>;

        // Line Chart Configuration (Employee Growth)
        const ctxGrowth = document.getElementById('growthChart').getContext('2d');
        new Chart(ctxGrowth, {
            type: 'line',
            data: {
                labels: Object.keys(growthData),
                datasets: [{
                    label: 'Employees Hired',
                    data: Object.values(growthData),
                    borderColor: '#4f46e5',
                    borderWidth: 3,
                    pointBackgroundColor: '#4f46e5',
                    pointRadius: 5,
                    fill: true,
                    backgroundColor: (context) => {
                        const bg = context.chart.ctx.createLinearGradient(0, 0, 0, 300);
                        bg.addColorStop(0, 'rgba(79, 70, 229, 0.1)');
                        bg.addColorStop(1, 'rgba(79, 70, 229, 0)');
                        return bg;
                    },
                    tension: 0.35
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: true, position: 'top' } },
                scales: {
                    x: { grid: { display: false } },
                    y: { border: { dash: [5, 5] }, grid: { color: '#e2e8f0' }, beginAtZero: true }
                }
            }
        });

        // Donut Chart Configuration (Department Distribution)
        const ctxDept = document.getElementById('deptChart').getContext('2d');
        new Chart(ctxDept, {
            type: 'doughnut',
            data: {
                labels: departmentData.map(d => d.name),
                datasets: [{
                    data: departmentData.map(d => d.count),
                    backgroundColor: ['#0d6efd', '#0dcaf0', '#dee2e6', '#6c757d', '#ff6b6b', '#ffa500'],
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '75%'
            }
        });
    </script>
<script src="../../assets/js/submit-loading.js"></script>
</body>
</html>
