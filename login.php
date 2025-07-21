<?php
$page_title = 'Login - Courier Management System';
require_once 'includes/config.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    $role = $_SESSION['role'];
    header("Location: /$role/dashboard.php");
    exit();
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error_message = 'Please fill in all fields.';
    } else {
        // Check in all user tables
        $tables = [
            'admins' => 'admin',
            'agents' => 'agent', 
            'users' => 'user'
        ];
        
        $user_found = false;
        
        foreach ($tables as $table => $role) {
            $stmt = $pdo->prepare("SELECT id, name, email, password FROM $table WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['role'] = $role;
                
                header("Location: /$role/dashboard.php");
                exit();
            }
        }
        
        if (!$user_found) {
            $error_message = 'Invalid email or password.';
        }
    }
}

include 'includes/header.php';
?>

<div class="login-container">
    <div class="d-flex flex-column align-items-center">
        <!-- Login Form -->
        <div class="login-card">
            <div class="text-center mb-4">
                <h1 class="login-title">Sign In</h1>
                <p class="login-subtitle">Enter your credentials to access your account</p>
            </div>
            
            <?php if ($error_message): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <form id="loginForm" method="POST" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="Enter your email" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Enter your password" required>
                        <span class="input-group-text password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Sign In
                </button>
            </form>
            
            <div class="text-center mt-3">
                <p class="text-muted">Don't have an account? <a href="register.php" class="text-decoration-none">Register here</a></p>
            </div>
        </div>
        
        <!-- Demo Credentials -->
        <div class="demo-card">
            <h5 class="mb-3">Demo Credentials</h5>
            <p class="text-muted small mb-3">Click any button below to auto-fill login credentials</p>
            
            <div class="d-grid gap-2">
                <button type="button" class="btn demo-btn demo-admin" onclick="loginAsDemo('admin')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Admin</strong>
                            <br><small class="text-muted">Full system access</small>
                        </div>
                        <i class="fas fa-user-shield text-primary"></i>
                    </div>
                </button>
                
                <button type="button" class="btn demo-btn demo-agent" onclick="loginAsDemo('agent')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Agent</strong>
                            <br><small class="text-muted">Courier management</small>
                        </div>
                        <i class="fas fa-user-tie text-info"></i>
                    </div>
                </button>
                
                <button type="button" class="btn demo-btn demo-user" onclick="loginAsDemo('user')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>User</strong>
                            <br><small class="text-muted">Track packages</small>
                        </div>
                        <i class="fas fa-user text-success"></i>
                    </div>
                </button>
            </div>
            
            <div class="alert alert-info mt-3 mb-0" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                <small>Password for all demo accounts is either <strong>password</strong> or <strong>123456</strong></small>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>