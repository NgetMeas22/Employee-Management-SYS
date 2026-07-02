<?php
require_once __DIR__ . "/../../includes/auth.php";
require_login();

// Mock data simulating database results for the table
$recent_hires = [
    ['name' => 'Alex Rivera', 'role' => 'Senior Engineer', 'dept' => 'Engineering', 'date' => 'June 12, 2023', 'status' => 'Active', 'avatar' => 'https://i.pravatar.cc/150?img=32'],
    ['name' => 'Jordan Smith', 'role' => 'Lead Designer', 'dept' => 'Marketing', 'date' => 'June 08, 2023', 'status' => 'Active', 'avatar' => 'https://i.pravatar.cc/150?img=12'],
    ['name' => 'Sarah Chen', 'role' => 'Operations Manager', 'dept' => 'Operations', 'date' => 'June 05, 2023', 'status' => 'Onboarding', 'avatar' => 'https://i.pravatar.cc/150?img=47'],
    ['name' => 'Michael Brown', 'role' => 'Sales Associate', 'dept' => 'Marketing', 'date' => 'May 28, 2023', 'status' => 'Active', 'avatar' => 'https://i.pravatar.cc/150?img=53'],
    ['name' => 'Emily Davis', 'role' => 'HR Specialist', 'dept' => 'HR & Admin', 'date' => 'May 22, 2023', 'status' => 'Active', 'avatar' => 'https://i.pravatar.cc/150?img=31']
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="shortcut icon" href="../../assets/images/profile.jpg" type="image/jpeg">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body {
            background: #f8fafc;
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
            color: #64748b;
            background-color: #f8fafc;
        }
        .chart-container {
            position: relative;
            height: 260px;
            width: 100%;
        }
    </style>
</head>
<body>
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
                            <button class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 border-secondary-subtle text-dark bg-white">
                                <i class="bi bi-download"></i> Export Report
                            </button>
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
                                            <i class="bi bi-graph-up"></i> +4%
                                        </span>
                                    </div>
                                    <h6 class="text-muted text-uppercase small fw-bold tracking-wider mb-1">Total Employees</h6>
                                    <h2 class="fw-bold mb-0">254</h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="metric-icon"><i class="bi bi-building fs-5"></i></div>
                                        <span class="badge bg-light text-secondary border d-flex align-items-center gap-1">Steady</span>
                                    </div>
                                    <h6 class="text-muted text-uppercase small fw-bold tracking-wider mb-1">Total Departments</h6>
                                    <h2 class="fw-bold mb-0">12</h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="metric-icon"><i class="bi bi-person-check-fill fs-5"></i></div>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle d-flex align-items-center gap-1">
                                            <i class="bi bi-check-circle"></i> 94%
                                        </span>
                                    </div>
                                    <h6 class="text-muted text-uppercase small fw-bold tracking-wider mb-1">Active Employees</h6>
                                    <h2 class="fw-bold mb-0">240</h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="metric-icon"><i class="bi bi-person-plus-fill fs-5"></i></div>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">New</span>
                                    </div>
                                    <h6 class="text-muted text-uppercase small fw-bold tracking-wider mb-1">New This Month</h6>
                                    <h2 class="fw-bold mb-0">18</h2>
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
                                        <small class="text-muted">Growth statistics for the current year</small>
                                    </div>
                                    <select class="form-select form-select-sm w-auto border-light-subtle bg-light">
                                        <option>Last 6 Months</option>
                                        <option>Last Year</option>
                                    </select>
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
                                        <div class="d-flex justify-content-between align-items-center small">
                                            <span><i class="bi bi-circle-fill text-primary me-2 fs-7"></i>Engineering</span>
                                            <span class="fw-semibold">35%</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center small">
                                            <span><i class="bi bi-circle-fill text-info me-2 fs-7"></i>Marketing</span>
                                            <span class="fw-semibold">25%</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center small">
                                            <span><i class="bi bi-circle-fill text-secondary-subtle me-2 fs-7"></i>Operations</span>
                                            <span class="fw-semibold">20%</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center small">
                                            <span><i class="bi bi-circle-fill text-secondary me-2 fs-7"></i>HR & Admin</span>
                                            <span class="fw-semibold">20%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-bold mb-0">Recent Hires</h5>
                                <small class="text-muted">Last 5 employees onboarded</small>
                            </div>
                            <a href="../employees/index.php" class="btn btn-link btn-sm text-decoration-none fw-semibold">View All</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Employee</th>
                                        <th>Department</th>
                                        <th>Joining Date</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_hires as $hire): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="<?= $hire['avatar'] ?>" class="avatar" alt="Avatar">
                                                <div>
                                                    <h6 class="mb-0 fw-semibold text-dark"><?= htmlspecialchars($hire['name']) ?></h6>
                                                    <small class="text-muted"><?= htmlspecialchars($hire['role']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-secondary"><?= htmlspecialchars($hire['dept']) ?></span>
                                        </td>
                                        <td>
                                            <span class="text-secondary"><?= htmlspecialchars($hire['date']) ?></span>
                                        </td>
                                        <td>
                                            <?php if ($hire['status'] === 'Active'): ?>
                                                <span class="badge bg-success-subtle text-success px-2.5 py-1.5 border border-success-subtle">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-primary-subtle text-primary px-2.5 py-1.5 border border-primary-subtle">Onboarding</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-link text-secondary p-0" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Line Chart Configuration (Employee Growth)
        const ctxGrowth = document.getElementById('growthChart').getContext('2d');
        new Chart(ctxGrowth, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Growth',
                    data: [190, 205, 215, 228, 235, 254],
                    borderColor: '#4f46e5',
                    borderWidth: 3,
                    pointBackgroundColor: '#4f46e5',
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
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { border: { dash: [5, 5] }, grid: { color: '#e2e8f0' } }
                }
            }
        });

        // Donut Chart Configuration (Department Distribution)
        const ctxDept = document.getElementById('deptChart').getContext('2d');
        new Chart(ctxDept, {
            type: 'doughnut',
            data: {
                labels: ['Engineering', 'Marketing', 'Operations', 'HR & Admin'],
                datasets: [{
                    data: [35, 25, 20, 20],
                    backgroundColor: ['#0d6efd', '#0dcaf0', '#dee2e6', '#6c757d'],
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
</body>
</html>
