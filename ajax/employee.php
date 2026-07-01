<?php
require_once __DIR__ . "/../../includes/auth.php";
require_login();

// Mock data representing the exact table items in your image
$employees = [
    ['name' => 'Sarah Jenkins', 'email' => 'sarah.j@workforce.pro', 'role' => 'Senior UX Designer', 'dept' => 'Design', 'status' => 'Active', 'avatar' => 'https://i.pravatar.cc/150?img=47'],
    ['name' => 'David Miller', 'email' => 'd.miller@workforce.pro', 'role' => 'Lead Developer', 'dept' => 'Engineering', 'status' => 'On Leave', 'avatar' => 'https://i.pravatar.cc/150?img=33'],
    ['name' => 'Robert Wilson', 'email' => 'r.wilson@workforce.pro', 'role' => 'Director of Ops', 'dept' => 'Management', 'status' => 'Active', 'avatar' => 'https://i.pravatar.cc/150?img=68'],
    ['name' => 'Emily Thompson', 'email' => 'e.thompson@workforce.pro', 'role' => 'HR Manager', 'dept' => 'Human Resources', 'status' => 'Active', 'avatar' => 'https://i.pravatar.cc/150?img=32'],
    ['name' => 'James Peterson', 'email' => 'j.peter@workforce.pro', 'role' => 'Product Marketer', 'dept' => 'Marketing', 'status' => 'Active', 'avatar' => 'https://i.pravatar.cc/150?img=59']
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees Directory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            background: #f8fafc;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #1e293b;
        }
        .filter-card, .directory-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }
        .form-select-custom {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 0.5rem 2rem 0.5rem 0.75rem;
            font-size: 0.875rem;
            color: #334155;
            background-color: #ffffff;
        }
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #64748b;
            background-color: #f8fafc;
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .table td {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
        }
        .badge-active {
            background-color: #e2fbe8;
            color: #1e8a3d;
            font-weight: 500;
        }
        .badge-leave {
            background-color: #f1f5f9;
            color: #64748b;
            font-weight: 500;
        }
        .pagination .page-link {
            color: #334155;
            border-color: #e2e8f0;
            padding: 0.5rem 0.75rem;
            margin: 0 2px;
            border-radius: 6px;
        }
        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: #ffffff;
        }
        .metric-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            height: 100%;
        }
        .stacked-avatars img {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid #ffffff;
            margin-right: -8px;
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
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="fw-bold mb-1">Employees</h2>
                            <p class="text-muted mb-0">Manage and monitor team performance and availability.</p>
                        </div>
                        <button class="btn btn-primary d-inline-flex align-items-center gap-2 fw-medium px-4 py-2">
                            <a href="add_employee.php"><i class="bi bi-plus-lg"></i> Add Employee</a>
                        </button>
                    </div>

                    <div class="filter-card p-3 mb-4 shadow-sm">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            <div class="d-flex gap-3 flex-wrap">
                                <div>
                                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Department</label>
                                    <select class="form-select form-select-custom">
                                        <option>All Departments</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Status</label>
                                    <select class="form-select form-select-custom">
                                        <option>All Status</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center gap-3 justify-content-between justify-content-md-end">
                                <span class="text-muted small">Showing 1–10 of 1,248</span>
                                <div class="btn-group border rounded-2 bg-light p-0.5">
                                    <button class="btn btn-sm btn-light border-0 px-2.5 py-1.5"><i class="bi bi-grid-fill text-secondary"></i></button>
                                    <button class="btn btn-sm btn-white border-0 shadow-sm px-2.5 py-1.5 bg-white rounded-1"><i class="bi bi-list-task text-primary"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="directory-card shadow-sm mb-4">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;" class="ps-4">
                                            <input class="form-check-input" type="checkbox">
                                        </th>
                                        <th>Employee</th>
                                        <th>Role</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                        <th class="pe-4"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($employees as $emp): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="<?= $emp['avatar'] ?>" class="avatar" alt="Avatar">
                                                <div>
                                                    <h6 class="mb-0 fw-semibold"><?= htmlspecialchars($emp['name']) ?></h6>
                                                    <small class="text-muted"><?= htmlspecialchars($emp['email']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-dark fw-medium"><?= htmlspecialchars($emp['role']) ?></span>
                                        </td>
                                        <td>
                                            <span class="text-secondary"><?= htmlspecialchars($emp['dept']) ?></span>
                                        </td>
                                        <td>
                                            <?php if ($emp['status'] === 'Active'): ?>
                                                <span class="badge badge-active px-2.5 py-1.5 rounded-pill">Active</span>
                                            <?php else: ?>
                                                <span class="badge badge-leave px-2.5 py-1.5 rounded-pill">On Leave</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="pe-4 text-end">
                                            </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3 border-top gap-3 bg-light bg-opacity-25 rounded-bottom-12">
                            <span class="text-muted small">Showing 1 to 5 of 1,248 results</span>
                            <nav>
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a></li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item disabled"><span class="page-link border-0 bg-transparent">...</span></li>
                                    <li class="page-item"><a class="page-link" href="#">250</a></li>
                                    <li class="page-item"><a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="metric-card p-4 shadow-sm">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="text-muted fw-normal mb-0">Active Utilization</h6>
                                    <i class="bi bi-graph-up-arrow text-primary"></i>
                                </div>
                                <h2 class="fw-bold mb-3">94.2%</h2>
                                <div class="progress mb-3" style="height: 6px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 94.2%"></div>
                                </div>
                                <small class="text-muted">+2.4% from last quarter</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="metric-card p-4 shadow-sm">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="text-muted fw-normal mb-0">Average Seniority</h6>
                                    <i class="bi bi-journal-bookmark text-secondary"></i>
                                </div>
                                <h2 class="fw-bold mb-3">4.8 Yrs</h2>
                                <div class="d-flex align-items-center gap-1.5 mb-3">
                                    <i class="bi bi-circle-fill text-primary" style="font-size: 8px;"></i>
                                    <i class="bi bi-circle-fill text-primary" style="font-size: 8px;"></i>
                                    <i class="bi bi-circle-fill text-primary" style="font-size: 8px;"></i>
                                    <i class="bi bi-circle-fill text-primary" style="font-size: 8px;"></i>
                                    <i class="bi bi-circle-fill text-black-50" style="font-size: 8px;"></i>
                                    <span class="small fw-semibold ms-2 text-dark">Stable</span>
                                </div>
                                <small class="text-muted">Reflects high retention rate</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="metric-card p-4 shadow-sm">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="text-muted fw-normal mb-0">Internal Mobility</h6>
                                    <i class="bi bi-arrow-up-right-curve text-primary"></i>
                                </div>
                                <h2 class="fw-bold mb-3">12%</h2>
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <div class="stacked-avatars d-inline-flex">
                                        <img src="https://i.pravatar.cc/100?img=11" alt="">
                                        <img src="https://i.pravatar.cc/100?img=12" alt="">
                                        <img src="https://i.pravatar.cc/100?img=13" alt="">
                                    </div>
                                    <span class="small text-muted ms-2"><strong class="text-dark">8 Promotions</strong> this month</span>
                                </div>
                                <small class="text-muted">Growth opportunities identified</small>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
