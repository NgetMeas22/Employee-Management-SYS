<?php
require_once __DIR__ . "/includes/auth.php";
redirect_if_logged_in();

$messages = [
    'invalid' => ['danger', 'Please enter a valid name, email, and password.'],
    'password_mismatch' => ['danger', 'Passwords do not match.'],
    'email_exist' => ['warning', 'That email is already registered. Please login instead.'],
    'failed' => ['danger', 'Registration failed. Please check your database table and try again.'],
];

$notice = null;

if (isset($_GET['error'], $messages[$_GET['error']])) {
    $notice = $messages[$_GET['error']];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management System | Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f4f7fb;
        }

        .register-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .18);
        }

        .brand-panel {
            background: linear-gradient(135deg, #0d6efd, #1f2937);
            color: #fff;
            padding: 48px;
        }

        .form-panel {
            background: #fff;
            padding: 48px;
        }

        .logo {
            width: 72px;
            height: 72px;
            background: #0d6efd;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            font-size: 32px;
            margin-bottom: 20px;
        }

        .input-group-text {
            background: #0d6efd;
            color: #fff;
            border: none;
        }

        .form-control,
        .form-select {
            height: 50px;
        }

        .btn-register {
            height: 50px;
            border-radius: 10px;
            font-weight: 600;
        }

        a {
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .form-panel,
            .brand-panel {
                padding: 34px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card register-card">
            <div class="row g-0">
                <div class="col-md-5 d-none d-md-flex brand-panel flex-column justify-content-center">
                    <h2 class="fw-bold">Create Your Account</h2>
                    <p class="mt-3 mb-0">
                        Register an admin user, then login with the same email and password.
                    </p>
                </div>

                <div class="col-md-7 form-panel">
                    <div class="logo mx-auto">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>

                    <h2 class="text-center fw-bold">Register</h2>
                    <p class="text-center text-muted mb-4">Add your first system account</p>

                    <?php if ($notice): ?>
                        <div class="alert alert-<?= htmlspecialchars($notice[0]) ?> py-2" role="alert">
                            <?= htmlspecialchars($notice[1]) ?>
                        </div>
                    <?php endif; ?>

                    <form action="includes/auth.php" method="POST">
                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="bi bi-person-fill"></i>
                            </span>
                            <input type="text" class="form-control" name="username" placeholder="Full Name" required>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="bi bi-envelope-fill"></i>
                            </span>
                            <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="bi bi-lock-fill"></i>
                            </span>
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>

                        <div class="input-group mb-4">
                            <span class="input-group-text">
                                <i class="bi bi-shield-lock-fill"></i>
                            </span>
                            <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
                        </div>

                        <input type="hidden" name="role" value="admin">

                        <button class="btn btn-primary btn-register w-100" type="submit" name="register">
                            <i class="bi bi-person-check-fill"></i>
                            Register
                        </button>
                    </form>

                    <p class="text-center text-muted small mt-4 mb-0">
                        Already have an account?
                        <a href="login.php" class="fw-semibold">Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
