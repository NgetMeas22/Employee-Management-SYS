<?php
session_start();

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../includes/auth.php";

// Check if user is logged in
require_login();

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}


$action = $_POST['action'] ?? '';

function redirect_departments(string $query = ''): void
{
    redirect_to('pages/departments/index.php' . $query);
}

function clean_text(string $value): string
{
    return trim($value);
}

if ($action === 'create') {
    $name = clean_text($_POST['department_name'] ?? '');
    $description = clean_text($_POST['description'] ?? '');

    if ($name === '') {
        redirect_to('pages/departments/create.php?error=missing_name');
    }

    try {
        $stmt = $conn->prepare("INSERT INTO departments (department_name, description) VALUES (?, ?)");
        $stmt->bind_param('ss', $name, $description);

        if ($stmt->execute()) {
            redirect_departments('?status=created');
        }
    } catch (mysqli_sql_exception $exception) {
        if ((int) $exception->getCode() === 1062) {
            redirect_to('pages/departments/create.php?error=duplicate');
        }
    }

    redirect_to('pages/departments/create.php?error=save_failed');
}

if ($action === 'update') {
    $departmentId = (int) ($_POST['department_id'] ?? 0);
    $name = clean_text($_POST['department_name'] ?? '');
    $description = clean_text($_POST['description'] ?? '');

    if ($departmentId <= 0 || $name === '') {
        redirect_to('pages/departments/edit.php?id=' . $departmentId . '&error=invalid');
    }

    try {
        $stmt = $conn->prepare("UPDATE departments SET department_name = ?, description = ? WHERE department_id = ?");
        $stmt->bind_param('ssi', $name, $description, $departmentId);

        if ($stmt->execute()) {
            redirect_departments('?status=updated');
        }
    } catch (mysqli_sql_exception $exception) {
        if ((int) $exception->getCode() === 1062) {
            redirect_to('pages/departments/edit.php?id=' . $departmentId . '&error=duplicate');
        }
    }

    redirect_to('pages/departments/edit.php?id=' . $departmentId . '&error=save_failed');
}

if ($action === 'delete') {
    $departmentId = (int) ($_POST['department_id'] ?? 0);

    if ($departmentId <= 0) {
        redirect_departments('?error=invalid');
    }

    $employees = $conn->prepare("SELECT COUNT(*) AS total FROM employees WHERE department_id = ?");
    $employees->bind_param('i', $departmentId);
    $employees->execute();
    $employeeCount = (int) $employees->get_result()->fetch_assoc()['total'];

    if ($employeeCount > 0) {
        redirect_departments('?error=department_has_employees');
    }

    $stmt = $conn->prepare("DELETE FROM departments WHERE department_id = ?");
    $stmt->bind_param('i', $departmentId);

    if ($stmt->execute()) {
        redirect_departments('?status=deleted');
    }

    redirect_departments('?error=delete_failed');
}

redirect_departments('?error=unknown_action');




