<?php
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$filename = 'employee-report-' . date('Y-m-d') . '.csv';

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

$output = fopen('php://output', 'w');

// UTF-8 BOM lets spreadsheet applications display non-ASCII names correctly.
fwrite($output, "\xEF\xBB\xBF");

fputcsv($output, [
    'Employee ID',
    'Employee Code',
    'First Name',
    'Last Name',
    'Email',
    'Phone',
    'Position',
    'Department',
    'Hire Date',
    'Status',
    'Salary',
]);

$sql = "SELECT
            e.employee_id,
            e.employee_code,
            e.first_name,
            e.last_name,
            e.email,
            e.phone,
            e.position,
            COALESCE(d.department_name, '') AS department_name,
            e.hire_date,
            e.status,
            e.salary
        FROM employees e
        LEFT JOIN departments d ON d.department_id = e.department_id
        ORDER BY e.employee_id ASC";

$result = $conn->query($sql);

if ($result) {
    while ($employee = $result->fetch_assoc()) {
        fputcsv($output, [
            $employee['employee_id'],
            $employee['employee_code'],
            $employee['first_name'],
            $employee['last_name'],
            $employee['email'],
            $employee['phone'],
            $employee['position'],
            $employee['department_name'],
            $employee['hire_date'],
            $employee['status'],
            $employee['salary'],
        ]);
    }
}

fclose($output);
exit;
