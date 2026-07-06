<?php
$userId = $_SESSION['user_id'] ?? $_COOKIE['user_id'] ?? null;
$navbarPhoto = '';
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
$navbarPhotoUrl = $navbarPhoto ? app_base_url() . htmlspecialchars($navbarPhoto) : app_base_url() . 'assets/images/profile.jpg';
if ($navbarPhoto && file_exists(__DIR__ . '/../' . $navbarPhoto)) {
    $navbarPhotoUrl .= '?v=' . filemtime(__DIR__ . '/../' . $navbarPhoto);
}
?>
<nav class="navbar navbar-expand-lg bg-white border-bottom px-4 py-3">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
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
            <i class="bi bi-bell fs-5"></i>
            <i class="bi bi-envelope fs-5"></i>
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
    });
})();
</script>
