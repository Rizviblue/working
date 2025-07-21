<?php
$page_title = 'Agent Dashboard - Courier Management System';
require_once '../includes/config.php';
check_auth('agent');

// Get agent's city
$agent_query = "SELECT city FROM agents WHERE id = ?";
$agent_stmt = $pdo->prepare($agent_query);
$agent_stmt->execute([$_SESSION['user_id']]);
$agent = $agent_stmt->fetch();
$agent_city = $agent['city'];

// Get statistics for agent's area
$stats_query = "
    SELECT 
        COUNT(*) as total_couriers,
        SUM(CASE WHEN status = 'In Transit' THEN 1 ELSE 0 END) as in_transit,
        SUM(CASE WHEN status = 'Delivered' THEN 1 ELSE 0 END) as delivered,
        SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) as cancelled
    FROM couriers 
    WHERE pickup_city = ? OR delivery_city = ?
";
$stats_stmt = $pdo->prepare($stats_query);
$stats_stmt->execute([$agent_city, $agent_city]);
$stats = $stats_stmt->fetch();

// Get recent couriers for agent's area
$recent_query = "
    SELECT tracking_number, sender_name, receiver_name, pickup_city, delivery_city, status, created_at
    FROM couriers 
    WHERE pickup_city = ? OR delivery_city = ?
    ORDER BY created_at DESC 
    LIMIT 5
";
$recent_stmt = $pdo->prepare($recent_query);
$recent_stmt->execute([$agent_city, $agent_city]);
$recent_couriers = $recent_stmt->fetchAll();

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
                    <small class="text-muted">Agent Panel</small>
                </div>
            </a>
        </div>
        
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link active">
                    <i class="fas fa-chart-pie"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="add_courier.php" class="nav-link">
                    <i class="fas fa-plus"></i>
                    Add Courier
                </a>
            </li>
            <li class="nav-item">
                <a href="couriers.php" class="nav-link">
                    <i class="fas fa-list"></i>
                    My Couriers
                </a>
            </li>
            <li class="nav-item">
                <a href="reports.php" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    Reports
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
                <span class="badge bg-info me-3">
                    <i class="fas fa-map-marker-alt me-1"></i>
                    <?php echo htmlspecialchars($agent_city); ?>
                </span>
                
                <a href="add_courier.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Add Courier
                </a>
                
                <div class="dropdown profile-dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" 
                            data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i>
                        <span><?php echo get_user_name(); ?></span>
                        <span class="badge bg-info">Agent</span>
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="page-title">Agent Dashboard</h1>
                    <p class="page-subtitle">Manage couriers for <?php echo htmlspecialchars($agent_city); ?> area</p>
                </div>
            </div>
            
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-title">Total Couriers</span>
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?php echo number_format($stats['total_couriers']); ?></div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up me-1"></i>
                        Your area
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-title">In Transit</span>
                        <div class="stat-icon bg-info">
                            <i class="fas fa-truck"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?php echo number_format($stats['in_transit']); ?></div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up me-1"></i>
                        Active shipments
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-title">Delivered</span>
                        <div class="stat-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?php echo number_format($stats['delivered']); ?></div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up me-1"></i>
                        Completed
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-title">Cancelled</span>
                        <div class="stat-icon bg-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?php echo number_format($stats['cancelled']); ?></div>
                    <div class="stat-change negative">
                        <i class="fas fa-arrow-down me-1"></i>
                        Cancelled
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Recent Couriers -->
                <div class="col-lg-8">
                    <div class="recent-couriers">
                        <div class="section-header">
                            <h5 class="section-title">Recent Couriers - <?php echo htmlspecialchars($agent_city); ?></h5>
                            <a href="couriers.php" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        
                        <div class="courier-list">
                            <?php if (empty($recent_couriers)): ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No couriers found for your area yet.</p>
                                    <a href="add_courier.php" class="btn btn-primary">Add First Courier</a>
                                </div>
                            <?php else: ?>
                                <?php foreach ($recent_couriers as $courier): ?>
                                    <div class="courier-item">
                                        <div class="courier-info">
                                            <h6><?php echo '#' . $courier['tracking_number']; ?></h6>
                                            <small><?php echo htmlspecialchars($courier['sender_name'] . ' → ' . $courier['receiver_name']); ?></small>
                                        </div>
                                        <div class="courier-meta">
                                            <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $courier['status'])); ?>">
                                                <?php echo $courier['status']; ?>
                                            </span>
                                            <small><?php echo date('M j, g:i A', strtotime($courier['created_at'])); ?></small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="col-lg-4">
                    <div class="quick-actions">
                        <h5 class="section-title mb-3">Quick Actions</h5>
                        <a href="add_courier.php" class="action-btn">
                            <i class="fas fa-plus text-primary"></i>
                            <span>Add New Courier</span>
                        </a>
                        <a href="couriers.php" class="action-btn">
                            <i class="fas fa-list text-info"></i>
                            <span>View My Couriers</span>
                        </a>
                        <a href="reports.php" class="action-btn">
                            <i class="fas fa-download text-success"></i>
                            <span>Download Reports</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>