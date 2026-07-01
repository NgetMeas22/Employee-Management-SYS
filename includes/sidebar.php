<?php
$currentPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');

function sidebar_active(string $section): string
{
    global $currentPath;

    return str_contains($currentPath, "/pages/$section/")
        ? 'active bg-primary text-white'
        : 'text-dark';
}
?>

<div class="d-flex flex-column flex-shrink-0 p-3 bg-white border-end min-vh-100" style="width:260px;">
    <a href="../dashboard/index.php" class="d-flex align-items-center mb-4 text-decoration-none">
        <div class="bg-primary text-white rounded p-2 me-2">
            <i class="bi bi-people-fill"></i>
        </div>

        <div>
            <h5 class="m-0 fw-bold text-dark">Meas SYS</h5>
            <small class="text-muted">Enterprise HR</small>
        </div>
    </a>

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-2">
            <a href="../dashboard/index.php" class="nav-link <?= sidebar_active('dashboard') ?>">
                <i class="bi bi-grid me-2"></i>
                Dashboard
            </a>
        </li>

        <li class="mb-2">
            <a href="../employees/index.php" class="nav-link <?= sidebar_active('employees') ?>">
                <i class="bi bi-people me-2"></i>
                Employees
            </a>
        </li>

        <li class="mb-2">
            <a href="../departments/index.php" class="nav-link <?= sidebar_active('departments') ?>">
                <i class="bi bi-building me-2"></i>
                Departments
            </a>
        </li>

        <li class="mb-2">
            <a href="../profile/index.php" class="nav-link <?= sidebar_active('profile') ?>">
                <i class="bi bi-person me-2"></i>
                Profile
            </a>
        </li>

        <li>
            <a href="../../logout.php" class="nav-link text-dark">
                <i class="bi bi-box-arrow-right me-2"></i>
                Logout
            </a>
        </li>
    </ul>

    <hr>

    <div class="card border-0 bg-light">
        <div class="card-body">
            <small class="fw-bold">Storage Used</small>
            <div class="progress mt-2">
                <div class="progress-bar bg-primary" style="width:75%"></div>
            </div>
            <small class="text-muted">750 GB of 1 TB</small>
        </div>
    </div>
</div>
