<?php
/**
 * ================================================================
 * USER DASHBOARD - E-COMMERCE STORE
 * 
 * Displays user dashboard with order statistics and recent orders.
 * ================================================================
 */

$page_title = 'Dashboard';
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = getUserId();
$user_type = getUserType();

try {
    $pdo = getDBConnection();
    $user = getUserById($user_id);
    
    // Get recent orders
    $stmt = $pdo->prepare("
        SELECT * FROM orders 
        WHERE user_id = ? 
        ORDER BY created_at DESC 
        LIMIT 5
    ");
    $stmt->execute([$user_id]);
    $recent_orders = $stmt->fetchAll();
    
    // Get order stats
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(*) as total_orders,
            SUM(CASE WHEN order_status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN order_status = 'processing' THEN 1 ELSE 0 END) as processing,
            SUM(CASE WHEN order_status = 'shipped' THEN 1 ELSE 0 END) as shipped,
            SUM(CASE WHEN order_status = 'delivered' THEN 1 ELSE 0 END) as delivered,
            SUM(total_amount) as total_spent
        FROM orders 
        WHERE user_id = ?
    ");
    $stmt->execute([$user_id]);
    $stats = $stmt->fetch();
    
} catch (Exception $e) {
    error_log("Dashboard error: " . $e->getMessage());
    $user = null;
    $recent_orders = [];
    $stats = [];
}

require_once '../includes/header.php';
?>

<div class="container">
    <div class="dashboard-container">
        <!-- Welcome Header -->
        <div class="dashboard-header">
            <h1>Welcome, <?php echo $user['full_name'] ?? 'User'; ?>!</h1>
            <p><i class="fas fa-user"></i> <?php echo ucfirst($user_type); ?> Account</p>
        </div>
        
        <!-- ============================================================
        STATISTICS CARDS
        ============================================================ -->
        <div class="dashboard-stats">
            <div class="stat-card">
                <i class="fas fa-shopping-bag"></i>
                <h3><?php echo $stats['total_orders'] ?? 0; ?></h3>
                <p>Total Orders</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-clock"></i>
                <h3><?php echo $stats['pending'] ?? 0; ?></h3>
                <p>Pending Orders</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-truck"></i>
                <h3><?php echo $stats['shipped'] ?? 0; ?></h3>
                <p>Shipped Orders</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-check-circle"></i>
                <h3><?php echo $stats['delivered'] ?? 0; ?></h3>
                <p>Delivered Orders</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-dollar-sign"></i>
                <h3><?php echo formatPrice($stats['total_spent'] ?? 0); ?></h3>
                <p>Total Spent</p>
            </div>
        </div>
        
        <!-- ============================================================
        QUICK ACTIONS
        ============================================================ -->
        <div class="quick-actions">
            <h2>Quick Actions</h2>
            <div class="action-buttons">
                <a href="products.php" class="btn-primary">
                    <i class="fas fa-shopping-bag"></i> Continue Shopping
                </a>
                <a href="order-history.php" class="btn-secondary">
                    <i class="fas fa-history"></i> View All Orders
                </a>
                <a href="profile.php" class="btn-secondary">
                    <i class="fas fa-user"></i> My Profile
                </a>
                <a href="cart.php" class="btn-secondary">
                    <i class="fas fa-shopping-cart"></i> View Cart
                </a>
            </div>
        </div>
        
        <!-- ============================================================
        RECENT ORDERS
        ============================================================ -->
        <div class="recent-orders">
            <h2>Recent Orders</h2>
            <?php if (count($recent_orders) > 0): ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_orders as $order): ?>
                                <tr>
                                    <td><?php echo $order['order_number']; ?></td>
                                    <td><?php echo formatDate($order['created_at']); ?></td>
                                    <td><?php echo formatPrice($order['total_amount']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $order['order_status']; ?>">
                                            <?php echo ucfirst($order['order_status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="order-confirmation.php?order_id=<?php echo $order['id']; ?>" 
                                           class="btn-secondary btn-sm">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <p>You haven't placed any orders yet.</p>
                    <a href="products.php" class="btn-primary">Start Shopping</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.dashboard-container {
    padding: 30px 0;
}
.dashboard-header {
    margin-bottom: 30px;
}
.dashboard-header h1 {
    margin-bottom: 5px;
}
.dashboard-header p {
    color: var(--gray-text);
}
.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}
.stat-card {
    background: white;
    padding: 20px;
    border-radius: var(--border-radius);
    text-align: center;
    box-shadow: var(--box-shadow);
    transition: all var(--transition-speed) ease;
}
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--box-shadow-hover);
}
.stat-card i {
    font-size: 2rem;
    color: var(--primary-color);
    margin-bottom: 10px;
}
.stat-card h3 {
    font-size: 1.8rem;
    margin-bottom: 5px;
}
.stat-card p {
    color: var(--gray-text);
    font-size: 0.9rem;
}
.quick-actions {
    background: white;
    padding: 20px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    margin-bottom: 30px;
}
.quick-actions h2 {
    margin-bottom: 15px;
}
.action-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.recent-orders {
    background: white;
    padding: 20px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
}
.recent-orders h2 {
    margin-bottom: 15px;
}
.btn-sm {
    padding: 5px 12px;
    font-size: 0.8rem;
}
.badge-pending {
    color: var(--warning-color);
}
.badge-processing {
    color: var(--info-color);
}
.badge-shipped {
    color: var(--primary-color);
}
.badge-delivered {
    color: var(--success-color);
}
.badge-cancelled {
    color: var(--danger-color);
}
@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column;
    }
    .action-buttons a {
        width: 100%;
        text-align: center;
    }
}
</style>

<?php require_once '../includes/footer.php'; ?>