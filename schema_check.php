<?php
$c = mysqli_connect('localhost', 'root', '', 'meas_sys');
if (!$c) {
    echo 'connfailed\n';
    exit(1);
}
$res = $c->query('SHOW COLUMNS FROM employees');
if (!$res) {
    echo 'nocol\n';
    exit(1);
}
while ($r = $res->fetch_assoc()) {
    echo $r['Field'] . '|' . $r['Type'] . '|' . $r['Null'] . '|' . $r['Key'] . '|' . $r['Extra'] . "\n";
}
mysqli_close($c);
