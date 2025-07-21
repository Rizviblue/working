<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'courier_system');

// Create database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security functions
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function generate_tracking_number() {
    return 'CMS' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
}

function check_auth($required_role = null) {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        header('Location: /login.php');
        exit();
    }
    
    if ($required_role && $_SESSION['role'] !== $required_role) {
        header('Location: /login.php');
        exit();
    }
}

function get_user_name() {
    return $_SESSION['user_name'] ?? 'User';
}

function get_user_role() {
    return $_SESSION['role'] ?? 'user';
}
?>