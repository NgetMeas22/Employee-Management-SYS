<?php
$conn = new mysqli('localhost', 'root', '', 'meas_sys');

if ($conn->connect_error) {
    die('Database connection failed.');
}
