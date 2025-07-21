<?php
// Database configuration for MySQL
$db_host = 'localhost';
$db_name = 'courier_system';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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