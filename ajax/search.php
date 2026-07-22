<?php
require_once __DIR__ . "/../includes/auth.php";

header('Content-Type: application/json; charset=utf-8');

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['error' => 'not_authenticated']);
    exit;
}

$q = trim($_GET['q'] ?? '');
$out = ['employees' => [], 'departments' => []];

if ($q === '') {
    echo json_encode($out);
    exit;
}

$like = '%' . $conn->real_escape_string($q) . '%';

// Search employees (first_name, last_name, email, or full name)
$sqlEmp = "SELECT employee_id, CONCAT(first_name, ' ', last_name) AS name, email, position AS role FROM employees WHERE CONCAT(first_name, ' ', last_name) LIKE '$like' OR first_name LIKE '$like' OR last_name LIKE '$like' OR email LIKE '$like' LIMIT 7";
$res = $conn->query($sqlEmp);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $out['employees'][] = [
            'id' => $row['employee_id'],
            'name' => $row['name'],
            'email' => $row['email'],
            'role' => $row['role'] ?? ($row['position'] ?? '')
        ];
    }
}
// Search departments
$sqlDept = "SELECT department_id, department_name FROM departments WHERE department_name LIKE '$like' LIMIT 7";
$res = $conn->query($sqlDept);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $out['departments'][] = [
            'id' => $row['department_id'],
            'name' => $row['department_name']
        ];
    }
}

echo json_encode($out);
exit;
