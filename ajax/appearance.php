<?php
require_once __DIR__ . "/../includes/auth.php";
require_login();

$allowedThemes = ['light', 'dark', 'system'];
$theme = $_POST['theme'] ?? current_theme();

if (!in_array($theme, $allowedThemes, true)) {
    $theme = 'light';
}

$_SESSION['theme'] = $theme;
setcookie('theme', $theme, [
    'expires' => time() + 3600,
    'path' => '/',
    'samesite' => 'Lax',
]);

$userId = $_SESSION['user_id'] ?? $_COOKIE['user_id'] ?? null;
if ($userId && isset($conn) && $conn instanceof mysqli) {
    $themeColumn = $conn->query("SHOW COLUMNS FROM user_s LIKE 'theme'");
    if ($themeColumn && $themeColumn->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE user_s SET theme = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param('ss', $theme, $userId);
            $stmt->execute();
            $stmt->close();
        }
    }
}

$returnTo = $_POST['return_to'] ?? app_base_url() . 'pages/dashboard/index.php';
$baseUrl = app_base_url();

if (!is_string($returnTo) || strpos($returnTo, $baseUrl) !== 0) {
    $returnTo = $baseUrl . 'pages/dashboard/index.php';
}

header('Location: ' . $returnTo);
exit;
