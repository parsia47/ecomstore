<?php
/**
 * ================================================================
 * ADMIN DASHBOARD - E-COMMERCE STORE
 * 
 * Overview of site statistics and activity.
 * ================================================================
 */

$page_title = 'Dashboard';
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check admin access
if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../public/login.php');
    exit;
}

try {
    $pdo = getDBConnection();
    
    // Get statistics
    $stats = [
        'total_users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
        'total_products' => $pdo->query("SELECT COUNT(*) FROM products WHERE is_active = 1")->fetchColumn(),
        'total_orders' => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
        'pending_orders' => $pdo->query("SELECT COUNT(*) FROM orders WHERE order_status = 'pending'")->fetchColumn(),
        'total_revenue' => $pdo->query("SELECT SUM(total_amount) FROM orders WHERE order_status = 'delivered'")->fetchColumn(),
        'low_stock' => $pdo->query("SELECT COUNT(*) FROM products WHERE stock_quantity < 10 AND is_active = 1")->fetchColumn()
    ];
    
    // Get recent orders
    $recent_orders = $pdo->query("
        SELECT o.*, u.full_name as customer_name 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.created_at DESC 
        LIMIT 10
    ")->fetchAll();
    
    // Get top selling products
    $top_products = $pdo->query("
        SELECT p.name, SUM(oi.quantity) as total_sold, SUM(oi.quantity * oi.price) as revenue
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        GROUP BY oi.product_id
        ORDER BY total_sold DESC
        LIMIT 5
    ")->fetchAll();
    
} catch (Exception $e) {
    $stats = [];
    $recent_orders = [];
    $top_products = [];
    error_log("Admin dashboard error: " . $e->getMessage());
}

require_once '../includes/admin_header.php';
?>

<div class="admin-container">
    <h1>Admin Dashboard</h1>
    
    <!-- ============================================================
    STATISTICS
    ============================================================ -->
    <div class="stats-grid">
        <div class="stat-card">
            <i class="fas fa-users"></i>
            <h3><?php echo $stats['total_users'] ?? 0; ?></h3>
            <p>Total Users</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-box"></i>
            <h3><?php echo $stats['total_products'] ?? 0; ?></h3>
            <p>Active Products</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-shopping-cart"></i>
            <h3><?php echo $stats['total_orders'] ?? 0; ?></h3>
            <p>Total Orders</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-clock"></i>
            <h3><?php echo $stats['pending_orders'] ?? 0; ?></h3>
            <p>Pending Orders</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-dollar-sign"></i>
            <h3><?php echo formatPrice($stats['total_revenue'] ?? 0); ?></h3>
            <p>Total Revenue</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-exclamation-triangle"></i>
            <h3><?php echo $stats['low_stock'] ?? 0; ?></h3>
            <p>Low Stock Items</p>
        </div>
    </div>
    
    <!-- ============================================================
    RECENT ORDERS
    ============================================================ -->
    <div class="recent-activity">
        <h2>Recent Orders</h2>
        <?php if (count($recent_orders) > 0): ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_orders as $order): ?>
                            <tr>
                                <td><?php echo $order['order_number']; ?></td>
                                <td><?php echo $order['customer_name']; ?></td>
                                <td><?php echo formatPrice($order['total_amount']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $order['order_status']; ?>">
                                        <?php echo ucfirst($order['order_status']); ?>
                                    </span>
                                </td>
                                <td><?php echo formatDate($order['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No orders found.</div>
        <?php endif; ?>
    </div>
    
    <!-- ============================================================
    TOP SELLING PRODUCTS
    ============================================================ -->
    <div class="top-products">
        <h2>Top Selling Products</h2>
        <?php if (count($top_products) > 0): ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Units Sold</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($top_products as $product): ?>
                            <tr>
                                <td><?php echo $product['name']; ?></td>
                                <td><?php echo $product['total_sold']; ?></td>
                                <td><?php echo formatPrice($product['revenue']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No sales data available.</div>
        <?php endif; ?>
    </div>
</div>

<style>
.admin-container h1 {
    margin: 30px 0;
}
.stats-grid {
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
    font-size: 2.5rem;
    color: var(--primary-color);
    margin-bottom: 10px;
}
.stat-card h3 {
    font-size: 2rem;
    margin-bottom: 5px;
}
.stat-card p {
    color: var(--gray-text);
}
.recent-activity, .top-products {
    background: white;
    padding: 20px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    margin-bottom: 30px;
}
.recent-activity h2, .top-products h2 {
    margin-bottom: 15px;
}
</style>

<?php require_once '../includes/footer.php'; ?>