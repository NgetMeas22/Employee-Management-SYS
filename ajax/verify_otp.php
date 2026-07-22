<?php
require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'POST requests only.']);
    exit;
}

$otpCode = trim($_POST['otp_code'] ?? '');
$userId = (int) ($_SESSION['otp_user_id'] ?? 0);

if ($userId === 0 || !preg_match('/^\d{6}$/', $otpCode)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Enter the six-digit verification code.']);
    exit;
}

$statement = $conn->prepare(
    'SELECT u.id, u.username, u.email, u.role
     FROM user_otp o INNER JOIN user_s u ON u.id = o.user_id
     WHERE o.user_id = ? AND o.otp_code = ? AND o.expired_at > NOW()
     ORDER BY o.id DESC LIMIT 1'
);
$statement->bind_param('is', $userId, $otpCode);
$statement->execute();
$user = $statement->get_result()->fetch_assoc();

if (!$user) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'That code is invalid or has expired.']);
    exit;
}

$delete = $conn->prepare('DELETE FROM user_otp WHERE user_id = ?');
$delete->bind_param('i', $userId);
$delete->execute();

unset($_SESSION['otp_user_id'], $_SESSION['otp_expires_at']);
complete_login($user);

echo json_encode(['success' => true, 'redirect' => app_base_url() . 'pages/dashboard/index.php']);
