<?php
$page_title = 'Register - Courier Management System';
require_once 'includes/config.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    $role = $_SESSION['role'];
    header("Location: /$role/dashboard.php");
    exit();
}

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = sanitize_input($_POST['role']);
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $phone = sanitize_input($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $city = isset($_POST['city']) ? sanitize_input($_POST['city']) : '';
    
    // Validation
    if (empty($role) || empty($name) || empty($email) || empty($password)) {
        $error_message = 'Please fill in all required fields.';
    } elseif ($password !== $confirm_password) {
        $error_message = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error_message = 'Password must be at least 6 characters long.';
    } else {
        // Check if email already exists
        $tables = ['admins', 'agents', 'users'];
        $email_exists = false;
        
        foreach ($tables as $table) {
            $stmt = $pdo->prepare("SELECT id FROM $table WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $email_exists = true;
                break;
            }
        }
        
        if ($email_exists) {
            $error_message = 'Email address already exists.';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            try {
                if ($role === 'agent') {
                    $stmt = $pdo->prepare("INSERT INTO agents (name, email, password, phone, city) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$name, $email, $hashed_password, $phone, $city]);
                } else { // user
                    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$name, $email, $hashed_password, $phone]);
                }
                
                $success_message = 'Registration successful! You can now log in.';
            } catch (PDOException $e) {
                $error_message = 'Registration failed. Please try again.';
            }
        }
    }
}

include 'includes/header.php';
?>

<div class="login-container">
    <div class="login-card" style="max-width: 500px;">
        <div class="text-center mb-4">
            <h1 class="login-title">Create Account</h1>
            <p class="login-subtitle">Register as an Agent or User</p>
        </div>
        
        <?php if ($success_message): ?>
            <div class="alert alert-success" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label for="role" class="form-label">Register as</label>
                <select class="form-control" id="role" name="role" required onchange="toggleCityField()">
                    <option value="">Select Role</option>
                    <option value="agent">Agent</option>
                    <option value="user">User</option>
                </select>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone">
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div id="cityField" class="mb-3" style="display: none;">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="Enter your city">
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <span class="input-group-text password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            <span class="input-group-text password-toggle" onclick="togglePassword('confirm_password')">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-user-plus me-2"></i>
                Create Account
            </button>
        </form>
        
        <div class="text-center mt-3">
            <p class="text-muted">Already have an account? <a href="login.php" class="text-decoration-none">Sign in here</a></p>
        </div>
    </div>
</div>

<script>
function toggleCityField() {
    const role = document.getElementById('role').value;
    const cityField = document.getElementById('cityField');
    
    if (role === 'agent') {
        cityField.style.display = 'block';
        document.getElementById('city').required = true;
    } else {
        cityField.style.display = 'none';
        document.getElementById('city').required = false;
    }
}
</script>

<?php include 'includes/footer.php'; ?>