<?php

require_once __DIR__ . '/../vendor/autoload.php';

$client = new Google_Client();

$client->setClientId("YOUR_CLIENT_ID");
$client->setClientSecret("YOUR_CLIENT_SECRET");
$client->setRedirectUri("http://localhost/EMPLOYEE-MANAGEMENT-SYSTEM/google_callback.php");

$client->addScope("email");
$client->addScope("profile");