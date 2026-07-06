<?php
require_once __DIR__ . "/../includes/auth.php";
require_login();

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('pages/employees/index.php?error=method');
}

// Helpers
function clean($v) {
    return trim((string) ($v ?? ''));
}

$first_name = clean($_POST['first_name'] ?? '');
$last_name = clean($_POST['last_name'] ?? '');
$gender = clean($_POST['gender'] ?? '');
$dob = clean($_POST['dob'] ?? null);
$phone = clean($_POST['phone'] ?? '');
$email = clean($_POST['email'] ?? '');
$department_id = (int) ($_POST['department_id'] ?? 0);
$position = clean($_POST['position'] ?? '');
$salary = clean($_POST['salary'] ?? '0');
$address = clean($_POST['address'] ?? '');
$hire_date = clean($_POST['hire_date'] ?? null);
$status = clean($_POST['status'] ?? 'Active');

$validStatuses = ['Active', 'Inactive', 'On Leave', 'Resigned'];
if ($gender !== 'Male' && $gender !== 'Female') {
    redirect_to('pages/employees/create.php?error=invalid_gender');
}
if ($dob === '') {
    redirect_to('pages/employees/create.php?error=invalid_dob');
}
if (!is_numeric($salary) || (float) $salary < 0) {
    redirect_to('pages/employees/create.php?error=invalid_salary');
}
if ($first_name === '' || $last_name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $department_id <= 0 || $hire_date === '' || !in_array($status, $validStatuses, true)) {
    redirect_to('pages/employees/create.php?error=invalid');
}

// Handle avatar upload
$photo_path = null;
if (!empty($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['avatar'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg','jpeg','png','gif','webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($ext, $allowed, true) || strpos($mime, 'image/') !== 0) {
            redirect_to('pages/employees/create.php?error=invalid_image');
        }

        $uploadDir = __DIR__ . '/../uploads/employees';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = 'emp_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $dest = $uploadDir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $dest)) {
            // store relative path
            $photo_path = 'uploads/employees/' . $filename;
        }
    }
}

// Normalize salary to decimal
$salary = (float) str_replace([',','$',' '], ['', '', ''], $salary);

// Generate employee code
$employee_code = 'EMP' . strtoupper(substr(uniqid('', true), -6));

$stmt = $conn->prepare("INSERT INTO employees (employee_code, department_id, first_name, last_name, position, gender, dob, phone, email, salary, address, photo, hire_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    redirect_to('pages/employees/create.php?error=' . urlencode($conn->error));
}

$stmt->bind_param(
    'sisssssssdssss',
    $employee_code,
    $department_id,
    $first_name,
    $last_name,
    $position,
    $gender,
    $dob,
    $phone,
    $email,
    $salary,
    $address,
    $photo_path,
    $hire_date,
    $status
);

if (!$stmt->execute()) {
    redirect_to('pages/employees/create.php?error=' . urlencode($stmt->error));
}

redirect_to('pages/employees/index.php?status=created');
