<?php
// Database configuration for MySQL
$db_host = '127.0.0.1';
$db_port = '3306';
$db_name = 'courier_system';
$db_user = 'courier_user';
$db_pass = 'courier_pass';

try {
    $pdo = new PDO("mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Start session
session_start();

// Define base URL
$base_url = "http://localhost";

// Security functions
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function generate_token() {
    return bin2hex(random_bytes(32));
}

function check_login() {
    return isset($_SESSION['user_id']);
}

function get_user_role() {
    return $_SESSION['user_role'] ?? null;
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function check_auth($required_role = null) {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
        header('Location: /login.php');
        exit();
    }
    
    if ($required_role && $_SESSION['user_role'] !== $required_role) {
        header('Location: /login.php');
        exit();
    }
}

function get_user_name() {
    return $_SESSION['user_name'] ?? 'User';
}

function generate_tracking_number() {
    return 'CMS' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
}
?>