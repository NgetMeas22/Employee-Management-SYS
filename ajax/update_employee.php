<?php
require_once __DIR__ . "/../includes/auth.php";
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('pages/employees/index.php?error=method');
}

function clean($v) { return trim((string) ($v ?? '')); }

$id = (int) ($_POST['employee_id'] ?? 0);
$first_name = clean($_POST['first_name'] ?? '');
$last_name = clean($_POST['last_name'] ?? '');
$email = clean($_POST['email'] ?? '');
$phone = clean($_POST['phone'] ?? '');
$department_id = (int) ($_POST['department_id'] ?? 0);
$position = clean($_POST['position'] ?? '');
$salary = (float) str_replace([',','$',' '], ['', '', ''], $_POST['salary'] ?? '0');
$address = clean($_POST['address'] ?? '');
$hire_date = clean($_POST['hire_date'] ?? null);
$status = clean($_POST['status'] ?? 'Inactive');

if ($id <= 0 || $first_name === '' || $last_name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_to('pages/employees/edit.php?id=' . $id . '&error=invalid');
}

// handle avatar upload (replace existing)
$photo_path = null;
if (!empty($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['avatar'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!in_array($ext, $allowed, true) || strpos($mime, 'image/') !== 0) {
            redirect_to('pages/employees/edit.php?id=' . $id . '&error=invalid_image');
        }

        $uploadDir = __DIR__ . '/../uploads/employees';
        if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }
        $filename = 'emp_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $dest = $uploadDir . '/' . $filename;
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            $photo_path = 'uploads/employees/' . $filename;
        }
    }
}

// Build update query
if ($photo_path !== null) {
    $stmt = $conn->prepare("UPDATE employees SET department_id=?, first_name=?, last_name=?, position=?, phone=?, email=?, salary=?, address=?, photo=?, hire_date=?, status=? WHERE employee_id=?");
    $stmt->bind_param('isssssdssssi', $department_id, $first_name, $last_name, $position, $phone, $email, $salary, $address, $photo_path, $hire_date, $status, $id);
} else {
    $stmt = $conn->prepare("UPDATE employees SET department_id=?, first_name=?, last_name=?, position=?, phone=?, email=?, salary=?, address=?, hire_date=?, status=? WHERE employee_id=?");
    $stmt->bind_param('isssssdsssi', $department_id, $first_name, $last_name, $position, $phone, $email, $salary, $address, $hire_date, $status, $id);
}

if ($stmt->execute()) {
    redirect_to('pages/employees/index.php?status=updated');
}

redirect_to('pages/employees/edit.php?id=' . $id . '&error=update_failed');
