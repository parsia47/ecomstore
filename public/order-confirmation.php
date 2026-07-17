<?php
/**
 * ================================================================
 * ORDER CONFIRMATION PAGE - E-COMMERCE STORE
 * 
 * Displays order confirmation after successful checkout.
 * ================================================================
 */

$page_title = 'Order Confirmation';
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
if (!$order_id) {
    header('Location: dashboard.php');
    exit;
}

try {
    $pdo = getDBConnection();
    
    // Get order details
    $stmt = $pdo->prepare("
        SELECT o.*, u.full_name, u.email 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        WHERE o.id = ? AND o.user_id = ?
    ");
    $stmt->execute([$order_id, getUserId()]);
    $order = $stmt->fetch();
    
    if (!$order) {
        header('Location: dashboard.php');
        exit;
    }
    
    // Get order items
    $stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $stmt->execute([$order_id]);
    $order_items = $stmt->fetchAll();
    
} catch (Exception $e) {
    error_log("Order confirmation error: " . $e->getMessage());
    header('Location: dashboard.php');
    exit;
}

require_once '../includes/header.php';
?>

<div class="container">
    <div class="confirmation-page">
        <div class="confirmation-box">
            <!-- Success Icon -->
            <i class="fas fa-check-circle fa-5x" style="color: var(--success-color);"></i>
            <h1>Thank You for Your Order!</h1>
            <p>Your order has been placed successfully.</p>
            
            <div class="order-details">
                <!-- Order Information -->
                <div class="order-info">
                    <div class="info-item">
                        <strong>Order Number</strong>
                        <span><?php echo $order['order_number']; ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Date</strong>
                        <span><?php echo formatDate($order['created_at']); ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Total</strong>
                        <span><?php echo formatPrice($order['total_amount']); ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Status</strong>
                        <span class="badge badge-<?php echo $order['order_status']; ?>">
                            <?php echo ucfirst($order['order_status']); ?>
                        </span>
                    </div>
                </div>
                
                <!-- Order Items -->
                <div class="order-items">
                    <h3>Order Items</h3>
                    <?php foreach ($order_items as $item): ?>
                        <div class="order-item">
                            <span><?php echo $item['product_name']; ?></span>
                            <span><?php echo $item['quantity']; ?> × <?php echo formatPrice($item['price']); ?></span>
                            <span><?php echo formatPrice($item['quantity'] * $item['price']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Shipping Address -->
                <div class="shipping-info">
                    <h3>Shipping Address</h3>
                    <p><?php echo nl2br($order['shipping_address']); ?></p>
                </div>
                
                <!-- Action Buttons -->
                <div class="confirmation-actions">
                    <a href="dashboard.php" class="btn-primary">Go to Dashboard</a>
                    <a href="products.php" class="btn-secondary">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.confirmation-page {
    padding: 60px 0;
}
.confirmation-box {
    text-align: center;
    background: white;
    padding: 50px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    max-width: 800px;
    margin: 0 auto;
}
.confirmation-box i {
    margin-bottom: 20px;
}
.confirmation-box h1 {
    margin-bottom: 10px;
}
.confirmation-box > p {
    color: var(--gray-text);
    margin-bottom: 30px;
}
.order-details {
    text-align: left;
}
.order-info {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    background: var(--light-bg);
    padding: 20px;
    border-radius: var(--border-radius);
    margin-bottom: 20px;
}
.info-item {
    display: flex;
    flex-direction: column;
}
.info-item strong {
    color: var(--gray-text);
    font-size: 0.8rem;
    text-transform: uppercase;
}
.info-item span {
    font-size: 1.1rem;
    font-weight: 500;
}
.order-items {
    background: var(--light-bg);
    padding: 20px;
    border-radius: var(--border-radius);
    margin-bottom: 20px;
}
.order-items h3 {
    margin-bottom: 15px;
}
.order-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid var(--border-color);
}
.order-item:last-child {
    border-bottom: none;
}
.shipping-info {
    background: var(--light-bg);
    padding: 20px;
    border-radius: var(--border-radius);
    margin-bottom: 20px;
}
.shipping-info h3 {
    margin-bottom: 10px;
}
.confirmation-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
}
.confirmation-actions a {
    flex: 1;
    text-align: center;
    padding: 12px;
}
@media (max-width: 768px) {
    .confirmation-box {
        padding: 30px 20px;
    }
    .order-info {
        grid-template-columns: 1fr 1fr;
    }
    .confirmation-actions {
        flex-direction: column;
    }
}
</style>

<?php require_once '../includes/footer.php'; ?>