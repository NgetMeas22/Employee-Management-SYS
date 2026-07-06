<?php
require_once __DIR__ . "/../includes/auth.php";
require_login();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/style.css?v=appearance-theme">
    
    <style>
        body {
            background: #f8fafc;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #1e293b;
        }
        .form-container {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
        }
        .avatar-upload-wrapper {
            position: relative;
            width: 110px;
            height: 110px;
        }
        .avatar-preview {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 2px dashed #cbd5e1;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .avatar-preview:hover {
            border-color: #0d6efd;
            background: #f1f5f9;
        }
        .avatar-edit-badge {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 28px;
            height: 28px;
            background: #0d6efd;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #334155;
            margin-bottom: 0.5rem;
        }
        .form-control, .form-select {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
            color: #1e293b;
            background-color: #ffffff;
        }
        .form-control::placeholder {
            color: #94a3b8;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        }
        .status-banner {
            background-color: #f1f5f9;
            border-radius: 12px;
            padding: 1.25rem;
        }
        .form-footer {
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 1.25rem 2rem;
        }
        .footer-note {
            font-size: 0.75rem;
            color: #94a3b8;
        }
    </style>
</head>
<body <?= app_body_attributes() ?>>
    <div class="d-flex min-vh-100">
        <?php require_once __DIR__ . "/../includes/sidebar.php"; ?>

        <div class="flex-grow-1 d-flex flex-column">
            <?php require_once __DIR__ . "/../includes/navbar.php"; ?>

            <main class="flex-grow-1 p-4">
                <div class="container-fluid" style="max-width: 960px;">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="fw-bold mb-1">Add New Employee</h2>
                            <p class="text-muted mb-0 small">Complete the form below to register a new member to the enterprise directory.</p>
                        </div>
                        <a href="index.php" class="btn btn-link text-decoration-none text-secondary small fw-medium d-inline-flex align-items-center gap-1">
                            <i class="bi bi-arrow-left"></i> Back to List
                        </a>
                    </div>

                    <?php $departments_rs = $conn->query("SELECT department_id, department_name FROM departments ORDER BY department_name ASC"); ?>
                    <form action="process-employee.php" method="POST" enctype="multipart/form-data" class="form-container shadow-sm">
                        <div class="p-4 p-md-5">

                            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-4 mb-5">
                                <div class="avatar-upload-wrapper">
                                    <label for="avatar-input" class="avatar-preview">
                                        <i class="bi bi-camera-fill text-secondary fs-3 mb-1"></i>
                                        <span class="text-muted" style="font-size: 0.65rem; font-weight: 500; text-align:center; line-height:1.2;">Upload<br>Photo</span>
                                    </label>
                                    <input type="file" id="avatar-input" name="avatar" accept="image/*" class="d-none">
                                    <div class="avatar-edit-badge"><i class="bi bi-pencil-fill"></i></div>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-dark mb-1">Employee Identity</h5>
                                    <p class="text-muted small mb-1">Please provide a clear professional headshot.</p>
                                    <small class="text-muted block" style="font-size: 0.75rem;">Recommended size is 400×400px.</small>
                                </div>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="first_name" placeholder="e.g. Robert" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="last_name" placeholder="e.g. Robertson" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Gender</label>
                                    <select class="form-select" name="gender" required>
                                        <option value="" disabled selected>Select gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" name="dob">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" name="phone" placeholder="+1 (555) 000-0000">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" placeholder="r.robertson@company.com" required>
                                </div>
                            </div>

                            <hr class="text-muted my-4 opacity-25">

                            <h5 class="fw-bold text-dark mb-4">Employment Details</h5>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Department</label>
                                    <select class="form-select" name="department_id" required>
                                        <?php if ($departments_rs && $departments_rs->num_rows > 0): ?>
                                            <option value="" disabled selected>Select department</option>
                                            <?php while ($d = $departments_rs->fetch_assoc()): ?>
                                                <option value="<?= (int) $d['department_id'] ?>"><?= htmlspecialchars($d['department_name']) ?></option>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <option value="" disabled>No departments available</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Position</label>
                                    <input type="text" class="form-control" name="position" placeholder="Job position" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Annual Salary (USD)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted" style="font-size: 0.875rem;">$</span>
                                        <input type="text" class="form-control border-start-0 ps-1" name="salary" placeholder="85000">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Hire Date</label>
                                    <input type="date" class="form-control" name="hire_date">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Residential Address</label>
                                    <textarea class="form-control" name="address" rows="3" placeholder="Enter full street address, city, state, and zip code"></textarea>
                                </div>
                            </div>

                            <div class="status-banner d-flex align-items-center justify-content-between mt-5">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="text-primary border border-primary-subtle bg-white rounded-3 px-2.5 py-2">
                                        <i class="bi bi-shield-check fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Active Employment Status</h6>
                                        <p class="text-muted mb-0" style="font-size: 0.75rem;">When active, the employee will have immediate access to portal features.</p>
                                    </div>
                                </div>
                                <div class="form-check form-switch p-0 m-0">
                                    <input class="form-check-input" type="checkbox" role="switch" name="status" id="statusSwitch" checked style="width: 2.75em; height: 1.5em; cursor: pointer;">
                                </div>
                            </div>

                        </div>

                        <div class="form-footer d-flex flex-column flex-sm-row justify-content-end align-items-center gap-3">
                            <button type="button" class="btn btn-light border px-4 py-2 small fw-medium text-secondary bg-white w-100 w-sm-auto" onclick="window.history.back()">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4 py-2 small fw-medium d-inline-flex align-items-center justify-content-center gap-2 w-100 w-sm-auto">
                                <i class="bi bi-hdd-fill"></i> Save Employee
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <span class="footer-note d-inline-flex align-items-center gap-1.5">
                            <i class="bi bi-info-circle"></i> All entered data is protected under Enterprise Privacy Policy 2024.
                        </span>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
