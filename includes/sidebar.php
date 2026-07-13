<?php
$currentPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');

function sidebar_active(string $section): string
{
    global $currentPath;

    return str_contains($currentPath, "/pages/$section/")
        ? 'active bg-primary text-white'
        : 'text-dark';
}

function format_bytes(int $bytes, int $precision = 2): string
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $value = max($bytes, 0);
    $index = 0;

    while ($value >= 1024 && $index < count($units) - 1) {
        $value /= 1024;
        $index++;
    }

    return round($value, $precision) . ' ' . $units[$index];
}

function get_upload_storage_usage(): array
{
    $storagePath = dirname(__DIR__) . '/uploads';
    $totalBytes = 1024 * 1024 * 1024; // 1 GB

    if (!is_dir($storagePath)) {
        return ['used' => 0, 'total' => $totalBytes, 'percent' => 0, 'displayPercent' => 0];
    }
    $usedBytes = 0;
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($storagePath, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($iterator as $item) {
        if ($item->isFile()) {
            $usedBytes += $item->getSize();
        }
    }
    $percent = $totalBytes > 0 ? min(100, round(($usedBytes / $totalBytes) * 100, 2)) : 0;
    $displayPercent = $usedBytes > 0 ? max(1, $percent) : 0;

    return [
        'used' => $usedBytes,
        'total' => $totalBytes,
        'percent' => $percent,
        'displayPercent' => $displayPercent,
    ];
}
$storageUsage = get_upload_storage_usage();
?>
<div class="d-flex flex-column flex-shrink-0 p-3 bg-white border-end min-vh-100" style="width:280px;">
    <a href="<?= app_base_url() ?>pages/dashboard/index.php" class="d-flex align-items-center mb-4 text-decoration-none">
        <div class="bg-primary text-white rounded p-2 me-2">
            <i class="bi bi-people-fill"></i>
        </div>

        <div>
            <h4 class="m-0 fw-bold text-dark">GenZ SYS</h4>
            <small class="text-muted fw-bold">NG-Meas</small>
        </div>
    </a>

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-2">
            <a href="<?= app_base_url() ?>pages/dashboard/index.php" class="nav-link <?= sidebar_active('dashboard') ?>">
                <i class="bi bi-grid me-2"></i>
                Dashboard
            </a>
        </li>

        <li class="mb-2">
            <a href="<?= app_base_url() ?>pages/employees/index.php" class="nav-link <?= sidebar_active('employees') ?>">
                <i class="bi bi-people me-2"></i>
                Employees
            </a>
        </li>

        <li class="mb-2">
            <a href="<?= app_base_url() ?>pages/departments/index.php" class="nav-link <?= sidebar_active('departments') ?>">
                <i class="bi bi-building me-2"></i>
                Departments
            </a>
        </li>

        <li class="mb-2">
            <a href="<?= app_base_url() ?>pages/profile/index.php" class="nav-link <?= sidebar_active('profile') ?>">
                <i class="bi bi-person me-2"></i>
                Profile
            </a>
        </li>

        <li class="mb-2">
            <a href="<?= app_base_url() ?>pages/settings/index.php" class="nav-link <?= sidebar_active('settings') ?>">
                <i class="bi bi-gear me-2"></i>
                Settings
            </a>
        </li>
        
    </ul>

    <hr>

    <div class="card border-0 bg-light">
        <div class="card-body">
            <small class="fw-bold">Storage Used</small>
            <div class="progress mt-2">
                <div class="progress-bar bg-primary" role="progressbar"
                    style="width: <?= $storageUsage['displayPercent'] ?>%"
                    aria-valuenow="<?= $storageUsage['percent'] ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <small class="text-muted"><?= format_bytes($storageUsage['used']) ?> of <?= format_bytes($storageUsage['total']) ?></small>
        </div>
    </div>
</div>
