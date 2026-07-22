<?php
require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) {
    redirect_to('pages/dashboard/index.php');
}

if (empty($_SESSION['otp_user_id'])) {
    redirect_to('login.php?error=otp_required');
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Login | Employee Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center min-vh-100">
<main class="container" style="max-width: 460px">
    <section class="card shadow-sm border-0">
        <div class="card-body p-4 p-md-5">
            <h1 class="h3 text-center mb-2">Verify your login</h1>
            <p class="text-muted text-center">We sent a six-digit code to your email. It expires in 10 minutes.</p>
            <?php if (isset($_GET['status']) && $_GET['status'] === 'resent'): ?>
                <div class="alert alert-success">A new code has been sent.</div>
            <?php elseif (isset($_GET['error']) && $_GET['error'] === 'send'): ?>
                <div class="alert alert-danger">We could not send a new code. Please try again later.</div>
            <?php endif; ?>
            <div id="otpMessage" class="alert d-none" role="alert"></div>
            <form id="otpForm">
                <label for="otpCode" class="form-label">Verification code</label>
                <input id="otpCode" name="otp_code" class="form-control form-control-lg text-center" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" maxlength="6" required autofocus>
                <button class="btn btn-primary w-100 mt-3" type="submit">Verify and sign in</button>
            </form>
            <form action="includes/auth.php" method="post" class="text-center mt-3">
                <button class="btn btn-link" type="submit" name="resend_otp">Send a new code</button>
                <a class="btn btn-link" href="login.php">Back to login</a>
            </form>
        </div>
    </section>
</main>
<script>
document.getElementById('otpForm').addEventListener('submit', async function (event) {
    event.preventDefault();
    const form = event.currentTarget;
    const response = await fetch('ajax/verify_otp.php', { method: 'POST', body: new FormData(form) });
    const data = await response.json();
    if (data.success) { window.location.assign(data.redirect); return; }
    const message = document.getElementById('otpMessage');
    message.textContent = data.message || 'Unable to verify the code.';
    message.className = 'alert alert-danger';
});
</script>
</body>
</html>
