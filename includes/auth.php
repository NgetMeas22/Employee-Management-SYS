<?php
require_once __DIR__ . "/../config/database.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function app_base_url(): string
{
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $projectFolder = basename(dirname(__DIR__));
    $projectPath = '/' . $projectFolder . '/';
    $position = strpos($scriptName, $projectPath);

    if ($position !== false) {
        return substr($scriptName, 0, $position + strlen($projectPath));
    }

    return '/';
}

function redirect_to(string $path): void
{
    header('Location: ' . app_base_url() . ltrim($path, '/'));
    exit;
}

function is_logged_in(): bool
{
    return !empty($_SESSION['user_id']) || !empty($_COOKIE['user_id']);
}

function current_user_name(): string
{
    return $_SESSION['username'] ?? $_COOKIE['username'] ?? 'Admin';
}

function require_login(): void
{
    if (!is_logged_in()) {
        redirect_to('login.php?error=login_required');
    }
}

function redirect_if_logged_in(): void
{
    if (is_logged_in()) {
        redirect_to('pages/dashboard/index.php');
    }
}

function set_login_cookie(string $name, string $value): void
{
    setcookie($name, $value, [
        'expires' => time() + 3600,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function clear_login_cookie(string $name): void
{
    setcookie($name, '', [
        'expires' => time() - 3600,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

if (isset($_POST['logout']) || isset($_GET['logout'])) {
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    session_destroy();

    clear_login_cookie("user_id");
    clear_login_cookie("username");
    clear_login_cookie("role");

    redirect_to('login.php?status=logged_out');
}

/* REGISTER */
if (isset($_POST['register'])) {

    $username = $conn->real_escape_string(trim($_POST['username']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = $_POST['password'] ?? $_POST['pwd'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? $password;

    if ($username === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        redirect_to('register.php?error=invalid');
    }

    if ($password !== $confirmPassword) {
        redirect_to('register.php?error=password_mismatch');
    }

    $pwd = password_hash($password, PASSWORD_DEFAULT);

    $check = $conn->query("SELECT * FROM user_s WHERE email='$email'");

    if ($check->num_rows > 0) {
        redirect_to('register.php?error=email_exist');
    }

    $hasRoleColumn = false;
    $columns = $conn->query("SHOW COLUMNS FROM user_s LIKE 'role'");

    if ($columns && $columns->num_rows > 0) {
        $hasRoleColumn = true;
    }

    if ($hasRoleColumn) {
        $role = $conn->real_escape_string($_POST['role'] ?? 'admin');
        $sql = "INSERT INTO user_s(username,email,pwd,role)
                VALUES('$username','$email','$pwd','$role')";
    } else {
        $sql = "INSERT INTO user_s(username,email,pwd)
                VALUES('$username','$email','$pwd')";
    }

    if ($conn->query($sql)) {
        redirect_to('login.php?status=registered');
    } else {
        redirect_to('register.php?error=failed');
    }
}

/* LOGIN */
if (isset($_POST['login'])) {

    $email = $conn->real_escape_string(trim($_POST['email']));
    $pwd = $_POST['password'] ?? $_POST['pwd'] ?? '';

    $sql = "SELECT * FROM user_s WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        if (password_verify($pwd, $user['pwd'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'] ?? '';

            set_login_cookie("user_id", (string) $user['id']);
            set_login_cookie("username", $user['username']);
            set_login_cookie("role", $user['role'] ?? '');

            redirect_to('pages/dashboard/index.php');

        } else {
            redirect_to('login.php?error=password');
        }

    } else {
        redirect_to('login.php?error=email');
    }
}
