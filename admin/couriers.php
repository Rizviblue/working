<?php
$page_title = 'Courier List - Admin Panel';
require_once '../includes/config.php';
check_auth('admin');

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $courier_id = intval($_POST['courier_id']);
    $new_status = sanitize_input($_POST['status']);
    
    $stmt = $pdo->prepare("UPDATE couriers SET status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$new_status, $courier_id]);
    
    header('Location: couriers.php');
    exit();
}

// Handle deletions
if (isset($_GET['delete'])) {
    $courier_id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM couriers WHERE id = ?");
    $stmt->execute([$courier_id]);
    
    header('Location: couriers.php');
    exit();
}

// Get all couriers
$couriers_query = "
    SELECT c.*, 
           CASE 
               WHEN c.role = 'admin' THEN a.name 
               WHEN c.role = 'agent' THEN ag.name 
           END as created_by_name
    FROM couriers c
    LEFT JOIN admins a ON c.created_by = a.id AND c.role = 'admin'
    LEFT JOIN agents ag ON c.created_by = ag.id AND c.role = 'agent'
    ORDER BY c.created_at DESC
";
$couriers = $pdo->query($couriers_query)->fetchAll();

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
                <a href="add_courier.php" class="nav-link">
                    <i class="fas fa-plus"></i>
                    Add Courier
                </a>
            </li>
            <li class="nav-item">
                <a href="couriers.php" class="nav-link active">
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
                <a href="add_courier.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Add New Courier
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
                    <h1 class="page-title">Courier Management</h1>
                    <p class="page-subtitle">View and manage all courier shipments</p>
                </div>
            </div>
            
            <div class="table-container">
                <div class="section-header">
                    <h5 class="section-title">All Couriers (<?php echo count($couriers); ?>)</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-filter me-1"></i>
                            Filter
                        </button>
                        <button class="btn btn-sm btn-outline-success">
                            <i class="fas fa-download me-1"></i>
                            Export
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tracking #</th>
                                <th>Sender → Receiver</th>
                                <th>Route</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($couriers as $courier): ?>
                                <tr class="searchable-row">
                                    <td>
                                        <strong><?php echo $courier['tracking_number']; ?></strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?php echo htmlspecialchars($courier['sender_name']); ?></strong>
                                            <br>
                                            <small class="text-muted">to <?php echo htmlspecialchars($courier['receiver_name']); ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">
                                            <?php echo htmlspecialchars($courier['pickup_city']); ?> → 
                                            <?php echo htmlspecialchars($courier['delivery_city']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo $courier['courier_type']; ?></span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $courier['status'])); ?>">
                                            <?php echo $courier['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small><?php echo date('M j, Y', strtotime($courier['created_at'])); ?></small>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <form method="POST" class="dropdown-item-text">
                                                            <input type="hidden" name="courier_id" value="<?php echo $courier['id']; ?>">
                                                            <select name="status" class="form-select form-select-sm mb-2">
                                                                <option value="Pending" <?php echo $courier['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                                <option value="In Transit" <?php echo $courier['status'] === 'In Transit' ? 'selected' : ''; ?>>In Transit</option>
                                                                <option value="Delivered" <?php echo $courier['status'] === 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                                                <option value="Cancelled" <?php echo $courier['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                            </select>
                                                            <button type="submit" name="update_status" class="btn btn-sm btn-primary w-100">Update</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                            <a href="?delete=<?php echo $courier['id']; ?>" 
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirmDelete('Are you sure you want to delete this courier?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>