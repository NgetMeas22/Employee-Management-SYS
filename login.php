<?php
require_once __DIR__ . "/includes/auth.php";
redirect_if_logged_in();

$messages = [
    'email' => ['danger', 'No account was found for that email address.'],
    'password' => ['danger', 'The password is incorrect. Please try again.'],
    'login_required' => ['warning', 'Please login before opening the dashboard.'],
];

$statusMessages = [
    'logged_out' => ['success', 'You have been logged out successfully.'],
    'registered' => ['success', 'Account created. You can login with your email now.'],
];

$notice = null;

if (isset($_GET['error'], $messages[$_GET['error']])) {
    $notice = $messages[$_GET['error']];
} elseif (isset($_GET['status'], $statusMessages[$_GET['status']])) {
    $notice = $statusMessages[$_GET['status']];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Employee Management System | Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        *{
            font-family: 'Poppins', sans-serif;
        }
        body{
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .login-card{
            border:none;
            border-radius:20px;
            overflow:hidden;
            box-shadow:0 15px 40px rgba(0,0,0,.2);
            animation: fadeIn .7s ease;
        }

        .left-side{
            background:#0d6efd;
            position:relative;
        }

        .left-side img{
            width:100%;
            height:100%;
            object-fit:cover;
        }

        .overlay{
            position:absolute;
            inset:0;
            background:rgba(0,0,0,.35);
        }

        .left-content{
            position:absolute;
            top:50%;
            left:50%;
            transform:translate(-50%,-50%);
            color:#fff;
            text-align:center;
            z-index:2;
            width:85%;
        }

        .left-content h2{
            font-weight:700;
        }

        .login-form{
            padding:50px;
            background:white;
        }

        .logo{
            width:75px;
            height:75px;
            background:#0d6efd;
            color:white;
            display:flex;
            justify-content:center;
            align-items:center;
            margin:auto;
            border-radius:50%;
            font-size:35px;
            margin-bottom:20px;
        }

        .input-group-text{
            background:#0d6efd;
            color:white;
            border:none;
        }

        .form-control{
            height:50px;
            border-radius:0 10px 10px 0;
        }

        .input-group{
            box-shadow:0 2px 10px rgba(0,0,0,.08);
            border-radius:10px;
        }

        .btn-login{
            height:50px;
            border-radius:10px;
            font-weight:600;
            transition:.3s;
        }

        .btn-login:hover{
            transform:translateY(-2px);
            box-shadow:0 8px 18px rgba(13,110,253,.35);
        }

        a{
            text-decoration:none;
        }

        @keyframes fadeIn{
            from{
                opacity:0;
                transform:translateY(30px);
            }
            to{
                opacity:1;
                transform:translateY(0);
            }
        }

        @media(max-width:768px){

            .login-form{
                padding:35px;
            }

        }

    </style>

    <link rel="stylesheet" href="assets/css/style.css?v=appearance-theme">
</head>
<body <?= app_body_attributes() ?>>

<div class="container">

    <div class="card login-card">

        <div class="row g-0">

            <!-- Left -->

            <div class="col-md-6 d-none d-md-block left-side">

                <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085">

                <div class="overlay"></div>

                <div class="left-content">

                    <h2>Employee Management System</h2>

                    <p class="mt-3">
                        Manage employees, departments,
                        attendance and payroll easily.
                    </p>

                </div>

            </div>

        

            <div class="col-md-6 login-form d-flex align-items-center">

                <div class="w-100">

                    <div class="logo">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <h2 class="text-center fw-bold">
                        Welcome Back
                    </h2>

                    <p class="text-center text-muted mb-4">
                        Login to continue
                    </p>

                    <?php if ($notice): ?>
                        <div class="alert alert-<?= htmlspecialchars($notice[0]) ?> py-2" role="alert">
                            <?= htmlspecialchars($notice[1]) ?>
                        </div>
                    <?php endif; ?>

                    <form action="includes/auth.php" method="POST">

                        <div class="input-group mb-3">

                            <span class="input-group-text">
                                <i class="bi bi-envelope-fill"></i>
                            </span>

                            <input
                                type="email"
                                class="form-control"
                                placeholder="Email Address"
                                name="email"
                                required>

                        </div>

                        <div class="input-group mb-4">

                            <span class="input-group-text">
                                <i class="bi bi-lock-fill"></i>
                            </span>

                            <input
                                type="password"
                                class="form-control"
                                placeholder="Password"
                                name="password"
                                required>

                        </div>

                        <button
                            class="btn btn-primary btn-login w-100"
                            type="submit"
                            name="login">

                            <i class="bi bi-box-arrow-in-right"></i>
                            Login

                        </button>

                    </form>

                    <p class="text-center text-muted small mt-4 mb-0">
                        Don't have an account?
                        <a href="register.php" class="fw-semibold">Register Here</a>
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="assets/js/submit-loading.js"></script>
</body>
</html>
