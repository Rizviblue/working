<?php
$page_title = 'Admin Dashboard - Courier Management System';
require_once '../includes/config.php';
check_auth('admin');

// Get statistics
$stats_query = "
    SELECT 
        COUNT(*) as total_couriers,
        SUM(CASE WHEN status = 'In Transit' THEN 1 ELSE 0 END) as in_transit,
        SUM(CASE WHEN status = 'Delivered' THEN 1 ELSE 0 END) as delivered,
        SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) as cancelled,
        SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today_new,
        SUM(CASE WHEN DATE(created_at) = CURDATE() AND status = 'Delivered' THEN 1 ELSE 0 END) as today_delivered
    FROM couriers
";
$stats = $pdo->query($stats_query)->fetch();

// Get recent couriers
$recent_query = "
    SELECT tracking_number, sender_name, receiver_name, pickup_city, delivery_city, status, created_at
    FROM couriers 
    ORDER BY created_at DESC 
    LIMIT 5
";
$recent_couriers = $pdo->query($recent_query)->fetchAll();

// Get active agents count
$agents_count = $pdo->query("SELECT COUNT(*) as count FROM agents")->fetch()['count'];

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
                <button class="btn btn-primary">
                    <i class="fas fa-chart-line me-2"></i>
                    Analytics
                </button>
                <button class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>
                    Add Courier
                </button>
                
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
                    <h1 class="page-title">Admin Dashboard</h1>
                    <p class="page-subtitle">Monitor and manage your courier operations</p>
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
                        +8% from last month
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
                        +8% from yesterday
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
                        +15% this week
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
                        3% from last week
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Recent Couriers -->
                <div class="col-lg-8">
                    <div class="recent-couriers">
                        <div class="section-header">
                            <h5 class="section-title">Recent Couriers</h5>
                            <a href="couriers.php" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        
                        <div class="courier-list">
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
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions & Today's Activity -->
                <div class="col-lg-4">
                    <div class="quick-actions mb-4">
                        <h5 class="section-title mb-3">Quick Actions</h5>
                        <a href="add_courier.php" class="action-btn">
                            <i class="fas fa-plus text-primary"></i>
                            <span>Add New Courier</span>
                        </a>
                        <a href="agents.php" class="action-btn">
                            <i class="fas fa-users text-info"></i>
                            <span>Manage Agents</span>
                        </a>
                        <a href="reports.php" class="action-btn">
                            <i class="fas fa-chart-bar text-success"></i>
                            <span>View Reports</span>
                        </a>
                    </div>
                    
                    <div class="quick-actions">
                        <h5 class="section-title mb-3">Today's Activity</h5>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>New Couriers</span>
                            <span class="badge bg-primary"><?php echo $stats['today_new']; ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Deliveries</span>
                            <span class="badge bg-success"><?php echo $stats['today_delivered']; ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Active Agents</span>
                            <span class="badge bg-info"><?php echo $agents_count; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>