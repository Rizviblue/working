<?php
$page_title = 'User Dashboard - Courier Management System';
require_once '../includes/config.php';
check_auth('user');

$tracking_result = null;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['track_courier'])) {
    $tracking_number = sanitize_input($_POST['tracking_number']);
    
    if (!empty($tracking_number)) {
        $stmt = $pdo->prepare("
            SELECT tracking_number, sender_name, receiver_name, pickup_city, 
                   delivery_city, courier_type, weight, delivery_date, status, created_at
            FROM couriers 
            WHERE tracking_number = ?
        ");
        $stmt->execute([$tracking_number]);
        $tracking_result = $stmt->fetch();
        
        if (!$tracking_result) {
            $error_message = 'Tracking number not found. Please check and try again.';
        }
    } else {
        $error_message = 'Please enter a tracking number.';
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
                    <small class="text-muted">Customer Portal</small>
                </div>
            </a>
        </div>
        
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link active">
                    <i class="fas fa-search"></i>
                    Track Package
                </a>
            </li>
            <li class="nav-item">
                <a href="history.php" class="nav-link">
                    <i class="fas fa-history"></i>
                    Tracking History
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
                    <input type="text" class="form-control search-input" placeholder="Enter tracking number...">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </div>
            
            <div class="header-actions">
                <div class="dropdown profile-dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" 
                            data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i>
                        <span><?php echo get_user_name(); ?></span>
                        <span class="badge bg-success">Customer</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>
        
        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <div class="text-center mb-5">
                <h1 class="page-title">Track Your Package</h1>
                <p class="page-subtitle">Enter your tracking number to get real-time updates</p>
            </div>
            
            <!-- Tracking Form -->
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="form-container">
                        <form method="POST" action="">
                            <div class="row align-items-end">
                                <div class="col-md-8">
                                    <label for="tracking_number" class="form-label">Tracking Number</label>
                                    <input type="text" class="form-control form-control-lg" 
                                           id="tracking_number" name="tracking_number" 
                                           placeholder="Enter tracking number (e.g., CMS001234)"
                                           value="<?php echo isset($_POST['tracking_number']) ? htmlspecialchars($_POST['tracking_number']) : ''; ?>">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" name="track_courier" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-search me-2"></i>
                                        Track Package
                                    </button>
                                </div>
                            </div>
                        </form>
                        
                        <?php if ($error_message): ?>
                            <div class="alert alert-warning mt-3" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Tracking Results -->
            <?php if ($tracking_result): ?>
                <div class="row justify-content-center mt-4">
                    <div class="col-lg-10">
                        <div class="form-container">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3>
                                    <i class="fas fa-box me-2"></i>
                                    Tracking: <?php echo $tracking_result['tracking_number']; ?>
                                </h3>
                                <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $tracking_result['status'])); ?> fs-6">
                                    <?php echo $tracking_result['status']; ?>
                                </span>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h5><i class="fas fa-user me-2"></i>Sender Information</h5>
                                    <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($tracking_result['sender_name']); ?></p>
                                    <p class="mb-3"><strong>Location:</strong> <?php echo htmlspecialchars($tracking_result['pickup_city']); ?></p>
                                    
                                    <h5><i class="fas fa-info-circle me-2"></i>Package Details</h5>
                                    <p class="mb-1"><strong>Type:</strong> <?php echo $tracking_result['courier_type']; ?></p>
                                    <?php if ($tracking_result['weight']): ?>
                                        <p class="mb-1"><strong>Weight:</strong> <?php echo $tracking_result['weight']; ?> kg</p>
                                    <?php endif; ?>
                                    <p class="mb-1"><strong>Shipped:</strong> <?php echo date('M j, Y g:i A', strtotime($tracking_result['created_at'])); ?></p>
                                    <?php if ($tracking_result['delivery_date']): ?>
                                        <p class="mb-1"><strong>Expected Delivery:</strong> <?php echo date('M j, Y', strtotime($tracking_result['delivery_date'])); ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-6">
                                    <h5><i class="fas fa-user-tag me-2"></i>Receiver Information</h5>
                                    <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($tracking_result['receiver_name']); ?></p>
                                    <p class="mb-3"><strong>Location:</strong> <?php echo htmlspecialchars($tracking_result['delivery_city']); ?></p>
                                    
                                    <h5><i class="fas fa-route me-2"></i>Delivery Route</h5>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="text-center">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                            <small class="d-block mt-1"><?php echo htmlspecialchars($tracking_result['pickup_city']); ?></small>
                                        </div>
                                        <div class="flex-grow-1">
                                            <hr class="my-0">
                                        </div>
                                        <div class="text-center">
                                            <div class="<?php echo $tracking_result['status'] === 'Delivered' ? 'bg-success' : 'bg-secondary'; ?> text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-flag-checkered"></i>
                                            </div>
                                            <small class="d-block mt-1"><?php echo htmlspecialchars($tracking_result['delivery_city']); ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center mt-4">
                                <button onclick="window.print()" class="btn btn-outline-primary">
                                    <i class="fas fa-print me-2"></i>
                                    Print Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Sample Tracking Numbers -->
            <div class="row justify-content-center mt-5">
                <div class="col-lg-8">
                    <div class="text-center">
                        <h5 class="mb-3">Try Demo Tracking Numbers</h5>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <button class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('tracking_number').value='CMS001234'">CMS001234</button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('tracking_number').value='CMS001235'">CMS001235</button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('tracking_number').value='CMS001236'">CMS001236</button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('tracking_number').value='CMS001237'">CMS001237</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>