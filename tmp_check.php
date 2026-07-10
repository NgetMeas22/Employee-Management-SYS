<?php
$c = new mysqli('localhost', 'root', '', 'meas_sys');
if ($c->connect_error) {
    die('Connect failed: ' . $c->connect_error);
}
echo "Connected: " . $c->server_info . "\n\n";

$result = $c->query("DESCRIBE employees");
echo "=== EMPLOYEES TABLE ===\n";
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . ' - ' . $row['Type'] . "\n";
}

echo "\n=== SAMPLE EMPLOYEES ===\n";
$result2 = $c->query("SELECT employee_id, first_name, last_name, photo FROM employees LIMIT 5");
while ($row = $result2->fetch_assoc()) {
    echo "ID: " . $row['employee_id'] . " | Name: " . $row['first_name'] . ' ' . $row['last_name'] . " | Photo: " . ($row['photo'] ?: '(none)') . "\n";
}

$c->close();
?>
