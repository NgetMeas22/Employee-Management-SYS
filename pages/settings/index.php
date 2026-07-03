<?php
require_once __DIR__ . "/../../includes/auth.php";
require_login();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $displayName = trim($_POST['display_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $notifications = isset($_POST['notifications']) ? '1' : '0';
    $theme = $_POST['theme'] ?? ($_SESSION['theme'] ?? 'light');
    $brandColor = $_POST['brand_color'] ?? ($_SESSION['brand_color'] ?? 'blue');
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (in_array($theme, ['light', 'dark', 'system'], true)) {
        $_SESSION['theme'] = $theme;
    }

    if (in_array($brandColor, ['blue', 'green', 'orange', 'charcoal'], true)) {
        $_SESSION['brand_color'] = $brandColor;
    }

    if ($newPassword !== '' && $newPassword !== $confirmPassword) {
        $message = 'New password and confirmation do not match.';
    } elseif ($newPassword !== '' && $currentPassword === '') {
        $message = 'Please enter your current password.';
    } else {
        $message = 'Settings updated successfully.';
    }
}

$displayNameValue = $_POST['display_name'] ?? current_user_name();
$emailValue = $_POST['email'] ?? 'admin@example.com';
$notificationsChecked = isset($_POST['notifications']) ? 'checked' : '';
$selectedTheme = current_theme();
$selectedBrandColor = current_brand_color();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
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
                    <div class="mb-4">
                        <h2 class="fw-bold mb-1">Settings</h2>
                        <p class="text-muted mb-0">Manage your account preferences and security options.</p>
                    </div>

                    <?php if ($message !== ''): ?>
                        <div class="alert alert-info mb-4">
                            <?= htmlspecialchars($message) ?>
                        </div>
                    <?php endif; ?>

                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="card shadow-sm">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-3">Profile Preferences</h5>
                                    <form method="post" id="preferencesForm">
                                        <div class="mb-3">
                                            <label class="form-label">Display Name</label>
                                            <input type="text" name="display_name" class="form-control" value="<?= htmlspecialchars($displayNameValue) ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($emailValue) ?>">
                                        </div>

                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="notifications" id="notifSwitch" value="1" <?= $notificationsChecked ?>>
                                            <label class="form-check-label" for="notifSwitch">Enable notifications</label>
                                        </div>

                                        <div class="appearance-panel mt-4">
                                            <div class="d-flex align-items-center gap-2 mb-3">
                                                <i class="bi bi-palette text-primary fs-5"></i>
                                                <h5 class="fw-bold mb-0">Appearance</h5>
                                            </div>

                                            <label class="form-label fw-semibold text-muted small mb-2">Theme Selection</label>
                                            <div class="theme-options mb-4">
                                                <input type="radio" class="btn-check" name="theme" id="themeLight" value="light" <?= $selectedTheme === 'light' ? 'checked' : '' ?>>
                                                <label class="theme-option" for="themeLight">
                                                    <i class="bi bi-brightness-high"></i>
                                                    <span>Light</span>
                                                </label>

                                                <input type="radio" class="btn-check" name="theme" id="themeDark" value="dark" <?= $selectedTheme === 'dark' ? 'checked' : '' ?>>
                                                <label class="theme-option" for="themeDark">
                                                    <i class="bi bi-moon"></i>
                                                    <span>Dark</span>
                                                </label>

                                                <input type="radio" class="btn-check" name="theme" id="themeSystem" value="system" <?= $selectedTheme === 'system' ? 'checked' : '' ?>>
                                                <label class="theme-option" for="themeSystem">
                                                    <i class="bi bi-window"></i>
                                                    <span>System</span>
                                                </label>
                                            </div>

                                            <label class="form-label fw-semibold text-muted small mb-2">Primary Brand Color</label>
                                            <div class="brand-options">
                                                <?php foreach (['blue' => '#0d6efd', 'green' => '#07864f', 'orange' => '#a43e06', 'charcoal' => '#4d4a57'] as $colorName => $colorHex): ?>
                                                    <input type="radio" class="btn-check" name="brand_color" id="brand<?= ucfirst($colorName) ?>" value="<?= htmlspecialchars($colorName) ?>" <?= $selectedBrandColor === $colorName ? 'checked' : '' ?>>
                                                    <label class="brand-swatch" for="brand<?= ucfirst($colorName) ?>" style="--swatch-color: <?= htmlspecialchars($colorHex) ?>;" aria-label="<?= htmlspecialchars(ucfirst($colorName)) ?>"></label>
                                                <?php endforeach; ?>

                                                <button type="button" class="brand-add" aria-label="Add brand color">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary mt-3">
                                            <i class="bi bi-save"></i>
                                            Save Preferences
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card shadow-sm">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-3">Security</h5>
                                    <form method="post">
                                        <div class="mb-3">
                                            <label class="form-label">Current Password</label>
                                            <input type="password" name="current_password" class="form-control">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">New Password</label>
                                            <input type="password" name="new_password" class="form-control">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Confirm Password</label>
                                            <input type="password" name="confirm_password" class="form-control">
                                        </div>

                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-shield-lock"></i>
                                            Update Password
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
        const preferencesForm = document.getElementById('preferencesForm');
        const themeInputs = document.querySelectorAll('input[name="theme"]');
        const brandInputs = document.querySelectorAll('input[name="brand_color"]');
        const brandColors = {
            blue: '#0d6efd',
            green: '#07864f',
            orange: '#a43e06',
            charcoal: '#4d4a57'
        };

        function applyAppearance() {
            const selectedTheme = document.querySelector('input[name="theme"]:checked')?.value || 'light';
            const selectedBrand = document.querySelector('input[name="brand_color"]:checked')?.value || 'blue';

            document.body.classList.remove('app-theme-light', 'app-theme-dark', 'app-theme-system');
            document.body.classList.remove('app-brand-blue', 'app-brand-green', 'app-brand-orange', 'app-brand-charcoal');
            document.body.classList.add(`app-theme-${selectedTheme}`, `app-brand-${selectedBrand}`);
            document.body.style.setProperty('--app-primary', brandColors[selectedBrand] || brandColors.blue);
        }

        [...themeInputs, ...brandInputs].forEach((input) => {
            input.addEventListener('change', () => {
                applyAppearance();
                preferencesForm.requestSubmit();
            });
        });
    </script>
</body>
</html>
