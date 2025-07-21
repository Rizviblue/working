<?php
$page_title = 'Add Courier - Admin Panel';
require_once '../includes/config.php';
check_auth('admin');

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tracking_number = generate_tracking_number();
    $sender_name = sanitize_input($_POST['sender_name']);
    $sender_phone = sanitize_input($_POST['sender_phone']);
    $sender_address = sanitize_input($_POST['sender_address']);
    $receiver_name = sanitize_input($_POST['receiver_name']);
    $receiver_phone = sanitize_input($_POST['receiver_phone']);
    $receiver_address = sanitize_input($_POST['receiver_address']);
    $pickup_city = sanitize_input($_POST['pickup_city']);
    $delivery_city = sanitize_input($_POST['delivery_city']);
    $courier_type = sanitize_input($_POST['courier_type']);
    $weight = floatval($_POST['weight']);
    $delivery_date = $_POST['delivery_date'];
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO couriers (tracking_number, sender_name, sender_phone, sender_address, 
                                receiver_name, receiver_phone, receiver_address, pickup_city, 
                                delivery_city, courier_type, weight, delivery_date, status, 
                                created_by, role) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', ?, 'admin')
        ");
        
        $stmt->execute([
            $tracking_number, $sender_name, $sender_phone, $sender_address,
            $receiver_name, $receiver_phone, $receiver_address, $pickup_city,
            $delivery_city, $courier_type, $weight, $delivery_date, $_SESSION['user_id']
        ]);
        
        $success_message = "Courier added successfully! Tracking number: $tracking_number";
    } catch (PDOException $e) {
        $error_message = "Error adding courier. Please try again.";
    }
}

include '../includes/header.php';
?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <a href="dashboard.php" class="sidebar-brand">
                <i class="fas fa-shipping-fast text-primary"></i>
                <div>
                    <div>CourierPro</div>
                    <small class="text-muted">Admin Panel</small>
                </div>
            </a>
        </div>
        
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link">
                    <i class="fas fa-chart-pie"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="add_courier.php" class="nav-link active">
                    <i class="fas fa-plus"></i>
                    Add Courier
                </a>
            </li>
            <li class="nav-item">
                <a href="couriers.php" class="nav-link">
                    <i class="fas fa-list"></i>
                    Courier List
                </a>
            </li>
            <li class="nav-item">
                <a href="agents.php" class="nav-link">
                    <i class="fas fa-users"></i>
                    Agent Management
                </a>
            </li>
            <li class="nav-item">
                <a href="customers.php" class="nav-link">
                    <i class="fas fa-user-friends"></i>
                    Customer Management
                </a>
            </li>
            <li class="nav-item">
                <a href="reports.php" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    Reports
                </a>
            </li>
            <li class="nav-item">
                <a href="settings.php" class="nav-link">
                    <i class="fas fa-cog"></i>
                    Settings
                </a>
            </li>
        </ul>
    </nav>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="search-box">
                <div class="position-relative">
                    <input type="text" class="form-control search-input" placeholder="Search couriers, tracking numbers...">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </div>
            
            <div class="header-actions">
                <a href="couriers.php" class="btn btn-outline-primary">
                    <i class="fas fa-list me-2"></i>
                    View All Couriers
                </a>
                
                <div class="dropdown profile-dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" 
                            data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i>
                        <span><?php echo get_user_name(); ?></span>
                        <span class="badge bg-primary">Admin</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>
        
        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="page-title">Add New Courier</h1>
                    <p class="page-subtitle">Create a new courier shipment</p>
                </div>
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
            
            <div class="form-container">
                <form method="POST" action="">
                    <div class="row">
                        <!-- Sender Information -->
                        <div class="col-lg-6">
                            <h5 class="mb-3"><i class="fas fa-user me-2"></i>Sender Information</h5>
                            
                            <div class="form-group">
                                <label for="sender_name" class="form-label">Sender Name *</label>
                                <input type="text" class="form-control" id="sender_name" name="sender_name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="sender_phone" class="form-label">Sender Phone</label>
                                <input type="tel" class="form-control" id="sender_phone" name="sender_phone">
                            </div>
                            
                            <div class="form-group">
                                <label for="sender_address" class="form-label">Sender Address *</label>
                                <textarea class="form-control" id="sender_address" name="sender_address" rows="3" required></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="pickup_city" class="form-label">Pickup City *</label>
                                <input type="text" class="form-control" id="pickup_city" name="pickup_city" required>
                            </div>
                        </div>
                        
                        <!-- Receiver Information -->
                        <div class="col-lg-6">
                            <h5 class="mb-3"><i class="fas fa-user-tag me-2"></i>Receiver Information</h5>
                            
                            <div class="form-group">
                                <label for="receiver_name" class="form-label">Receiver Name *</label>
                                <input type="text" class="form-control" id="receiver_name" name="receiver_name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="receiver_phone" class="form-label">Receiver Phone</label>
                                <input type="tel" class="form-control" id="receiver_phone" name="receiver_phone">
                            </div>
                            
                            <div class="form-group">
                                <label for="receiver_address" class="form-label">Receiver Address *</label>
                                <textarea class="form-control" id="receiver_address" name="receiver_address" rows="3" required></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="delivery_city" class="form-label">Delivery City *</label>
                                <input type="text" class="form-control" id="delivery_city" name="delivery_city" required>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Shipment Details -->
                    <h5 class="mb-3"><i class="fas fa-box me-2"></i>Shipment Details</h5>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="courier_type" class="form-label">Courier Type *</label>
                                <select class="form-control" id="courier_type" name="courier_type" required>
                                    <option value="">Select Type</option>
                                    <option value="Standard">Standard</option>
                                    <option value="Express">Express</option>
                                    <option value="Overnight">Overnight</option>
                                    <option value="International">International</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="weight" class="form-label">Weight (kg)</label>
                                <input type="number" class="form-control" id="weight" name="weight" step="0.01" min="0">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="delivery_date" class="form-label">Expected Delivery Date</label>
                                <input type="date" class="form-control" id="delivery_date" name="delivery_date" 
                                       min="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Add Courier
                        </button>
                        <a href="dashboard.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>