<?php
$userId = $_SESSION['user_id'] ?? $_COOKIE['user_id'] ?? null;
$navbarPhoto = '';
$employeeCount = 0;
$departmentCount = 0;
$navbarTheme = current_theme();
$navbarThemeLabels = [
    'light' => ['Light', 'bi-brightness-high'],
    'dark' => ['Dark', 'bi-moon'],
    'system' => ['System', 'bi-window'],
];
$navbarThemeLabel = $navbarThemeLabels[$navbarTheme] ?? $navbarThemeLabels['light'];
$navbarReturnTo = $_SERVER['REQUEST_URI'] ?? app_base_url() . 'pages/dashboard/index.php';
if ($userId) {
    $photoCol = $conn->query("SHOW COLUMNS FROM user_s LIKE 'photo'");
    if ($photoCol && $photoCol->num_rows > 0) {
        $escapedId = $conn->real_escape_string($userId);
        $photoRes = $conn->query("SELECT photo FROM user_s WHERE id='" . $escapedId . "' LIMIT 1");
        if ($photoRes && $photoRes->num_rows > 0) {
            $row = $photoRes->fetch_assoc();
            $navbarPhoto = $row['photo'] ?? '';
        }
    }
}
if (isset($conn) && $conn instanceof mysqli) {
    $employeesResult = $conn->query("SELECT COUNT(*) AS total FROM employees");
    if ($employeesResult) {
        $employeeCount = (int) ($employeesResult->fetch_assoc()['total'] ?? 0);
    }

    $departmentsResult = $conn->query("SELECT COUNT(*) AS total FROM departments");
    if ($departmentsResult) {
        $departmentCount = (int) ($departmentsResult->fetch_assoc()['total'] ?? 0);
    }
}
$navbarPhotoUrl = $navbarPhoto ? app_base_url() . htmlspecialchars($navbarPhoto) : app_base_url() . 'assets/images/profile.jpg';
if ($navbarPhoto && file_exists(__DIR__ . '/../' . $navbarPhoto)) {
    $navbarPhotoUrl .= '?v=' . filemtime(__DIR__ . '/../' . $navbarPhoto);
}
?>
<nav class="navbar navbar-expand-lg bg-white border-bottom px-4 py-3 position-sticky top-0 flex-shrink-0" style="z-index:1030;">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <button type="button" class="btn btn-light border d-lg-none me-2" id="sidebarToggle" aria-label="Open sidebar">
                <i class="bi bi-list fs-4"></i>
            </button>
            <div class="input-group search-box w-100 position-relative" style="max-width:500px;">
    <span class="input-group-text bg-white border-end-0 rounded-start-pill">
        <i class="bi bi-search"></i>
    </span>

    <input
        id="globalSearch"
        type="text"
        class="form-control border-start-0 rounded-end-pill"
        placeholder="Search employees, departments..."
        autocomplete="off"
    >
    <div id="searchResults" class="list-group position-absolute shadow-sm" style="z-index:2000; top:100%; left:0; right:0; display:none; max-height:360px; overflow:auto; border-radius:8px;"></div>
</div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="position-relative">
                <i class="bi bi-bell fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= (int) $employeeCount ?></span>
            </div>
            <div class="position-relative">
                <i class="bi bi-envelope fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary"><?= (int) $departmentCount ?></span>
            </div>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle navbar-theme-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi <?= htmlspecialchars($navbarThemeLabel[1]) ?>"></i>
                    <span><?= htmlspecialchars($navbarThemeLabel[0]) ?></span>
                </button>
                <div class="dropdown-menu dropdown-menu-end p-2 shadow-sm">
                    <?php foreach ($navbarThemeLabels as $themeValue => $themeOption): ?>
                        <form action="<?= app_base_url() ?>ajax/appearance.php" method="POST">
                            <input type="hidden" name="theme" value="<?= htmlspecialchars($themeValue) ?>">
                            <input type="hidden" name="return_to" value="<?= htmlspecialchars($navbarReturnTo) ?>">
                            <button type="submit" class="dropdown-item rounded d-flex align-items-center gap-2 <?= $navbarTheme === $themeValue ? 'active' : '' ?>">
                                <i class="bi <?= htmlspecialchars($themeOption[1]) ?>"></i>
                                <?= htmlspecialchars($themeOption[0]) ?>
                            </button>
                        </form>
                    <?php endforeach; ?>
                </div>
            </div>
            <a href="<?= app_base_url() ?>pages/profile/index.php">
                <img src="<?= htmlspecialchars($navbarPhotoUrl) ?>" class=" border border-2 border-dark-subtle rounded-circle" alt="Admin" width="40" height="40" style="object-fit: cover;">
            </a>
            <span class="fw-semibold"><?= htmlspecialchars(current_user_name()) ?></span>
            <a href="<?= app_base_url() ?>logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>
<script>
(() => {
    const input = document.getElementById('globalSearch');
    const results = document.getElementById('searchResults');
    let timer = null;

    function renderList(data) {
        results.innerHTML = '';
        // server-side error
        if (data && data.error) {
            results.innerHTML = '<div class="list-group-item text-warning small">' + escapeHtml(data.error) + '</div>';
            results.style.display = 'block';
            return;
        }
        let any = false;
        if (data.employees && data.employees.length) {
            any = true;
            const header = document.createElement('div');
            header.className = 'list-group-item small text-muted';
            header.textContent = 'Employees';
            results.appendChild(header);
            data.employees.forEach(emp => {
                const a = document.createElement('a');
                a.className = 'list-group-item list-group-item-action d-flex gap-2 align-items-center';
                a.href = '<?= app_base_url() ?>pages/employees/view.php?id=' + encodeURIComponent(emp.id);
                a.innerHTML = `<div class="flex-grow-1">
                    <div class="fw-semibold">${escapeHtml(emp.name)}</div>
                    <small class="text-muted">${escapeHtml(emp.email)} · ${escapeHtml(emp.role)}</small>
                </div>`;
                results.appendChild(a);
            });
        }
        if (data.departments && data.departments.length) {
            any = true;
            const header = document.createElement('div');
            header.className = 'list-group-item small text-muted';
            header.textContent = 'Departments';
            results.appendChild(header);

            data.departments.forEach(d => {
                const a = document.createElement('a');
                a.className = 'list-group-item list-group-item-action d-flex gap-2 align-items-center';
                a.href = '<?= app_base_url() ?>pages/departments/index.php?search=' + encodeURIComponent(d.name);
                a.innerHTML = `<div class="flex-grow-1">
                    <div class="fw-semibold">${escapeHtml(d.name)}</div>
                </div>`;
                results.appendChild(a);
            });
        }

        results.style.display = any ? 'block' : 'none';
    }

    function escapeHtml(s) {
        return String(s).replace(/[&<>"']/g, function(m) { return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]); });
    }

    input.addEventListener('input', (e) => {
        const q = e.target.value.trim();
        clearTimeout(timer);
        if (q.length < 2) {
            results.style.display = 'none';
            return;
        }
        timer = setTimeout(() => {
            const url = '<?= app_base_url() ?>ajax/search.php?q=' + encodeURIComponent(q);
            console.debug('Search request ->', url);
            // show temporary loading item
            results.innerHTML = '<div class="list-group-item small text-muted">Searching&hellip;</div>';
            results.style.display = 'block';

            fetch(url, { credentials:'same-origin' })
                .then(r => {
                    if (!r.ok) throw new Error('Network response was not ok: ' + r.status);
                    return r.text().then(txt => {
                        try {
                            return JSON.parse(txt);
                        } catch (e) {
                            throw new Error('Invalid JSON response (first 300 chars): ' + txt.slice(0,300));
                        }
                    });
                })
                .then(data => {
                    console.debug('Search response', data);
                    renderList(data);
                })
                .catch((err) => {
                    console.error('Search error', err);
                    const message = String(err.message || 'Search failed');
                    results.innerHTML = '<div class="list-group-item text-danger small">' + escapeHtml(message) + '</div>';
                    results.style.display = 'block';
                });
        }, 250);
    });

    // close on outside click
    document.addEventListener('click', (ev) => {
        if (!ev.target.closest('.search-box')) {
            results.style.display = 'none';
        }
        document.querySelectorAll('.dropdown-menu.show').forEach((menu) => {
            if (!menu.closest('.dropdown').contains(ev.target)) {
                menu.classList.remove('show');
                menu.closest('.dropdown').querySelector('[data-bs-toggle="dropdown"]')?.setAttribute('aria-expanded', 'false');
            }
        });
    });

    document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach((button) => {
        button.addEventListener('click', (ev) => {
            ev.preventDefault();
            const menu = button.closest('.dropdown')?.querySelector('.dropdown-menu');
            if (!menu) return;
            const isOpen = menu.classList.toggle('show');
            button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    });

    // mobile sidebar toggle
    const sidebar = document.getElementById('appSidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    const toggleBtn = document.getElementById('sidebarToggle');
    const closeBtn = document.getElementById('sidebarClose');

    function openSidebar() {
        if (!sidebar || !backdrop) return;
        sidebar.classList.add('show');
        backdrop.classList.add('show');
    }
    function closeSidebar() {
        if (!sidebar || !backdrop) return;
        sidebar.classList.remove('show');
        backdrop.classList.remove('show');
    }

    if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    if (backdrop) backdrop.addEventListener('click', closeSidebar);
    if (sidebar) {
        sidebar.addEventListener('click', (e) => {
            if (e.target.closest('.nav-link')) closeSidebar();
        });
    }
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeSidebar();
    });
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 992) closeSidebar();
    });
})();
</script>
