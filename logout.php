<?php
require_once __DIR__ . "/includes/auth.php";
require_login();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management System | Logout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=appearance-theme">
</head>
<body <?= app_body_attributes() ?>>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;">
                                <span class="fs-2">&rarr;</span>
                            </div>
                            <h3 class="card-title mb-2">Logout</h3>
                            <p class="text-muted mb-0">
                                <?= htmlspecialchars(current_user_name()) ?>, are you sure you want to sign out?
                            </p>
                        </div>
                        <form action="includes/auth.php" method="POST">
                            <input type="hidden" name="logout" value="1">
                            <button type="submit" class="btn btn-danger w-100">Logout</button>
                        </form>
                        <a href="pages/dashboard/index.php" class="btn btn-outline-primary  w-100 mt-2">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
