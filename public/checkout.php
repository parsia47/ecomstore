<?php
/**
 * ================================================================
 * CHECKOUT PAGE - E-COMMERCE STORE
 * 
 * Processes order and payment information.
 * ================================================================
 */

$page_title = 'Checkout';
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php?redirect=checkout.php');
    exit;
}

$user_id = getUserId();
$error = '';
$success = '';

try {
    $pdo = getDBConnection();
    
    // Get cart items
    $stmt = $pdo->prepare("
        SELECT c.*, p.name, p.price, p.stock_quantity 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll();
    
    if (empty($cart_items)) {
        header('Location: cart.php');
        exit;
    }
    
    // Calculate totals
    $subtotal = 0;
    foreach ($cart_items as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $tax = $subtotal * (TAX_RATE / 100);
    $total = $subtotal + $tax;
    
    // Get user info
    $user = getUserById($user_id);
    
    // Process order
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $shipping_address = sanitize($_POST['shipping_address'] ?? '');
        $payment_method = sanitize($_POST['payment_method'] ?? '');
        
        if (empty($shipping_address)) {
            $error = 'Please enter your shipping address.';
        } else {
            // Check stock before processing
            foreach ($cart_items as $item) {
                if ($item['stock_quantity'] < $item['quantity']) {
                    $error = "Not enough stock for {$item['name']}. Available: {$item['stock_quantity']}";
                    break;
                }
            }
            
            if (empty($error)) {
                // Create order
                $order_number = generateOrderNumber();
                $stmt = $pdo->prepare("
                    INSERT INTO orders (user_id, order_number, total_amount, shipping_address, payment_method, order_status) 
                    VALUES (?, ?, ?, ?, ?, 'pending')
                ");
                $stmt->execute([$user_id, $order_number, $total, $shipping_address, $payment_method]);
                $order_id = $pdo->lastInsertId();
                
                // Create order items and update stock
                foreach ($cart_items as $item) {
                    // Add order item
                    $stmt = $pdo->prepare("
                        INSERT INTO order_items (order_id, product_id, product_name, quantity, price) 
                        VALUES (?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([$order_id, $item['product_id'], $item['name'], $item['quantity'], $item['price']]);
                    
                    // Update stock
                    $stmt = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");
                    $stmt->execute([$item['quantity'], $item['product_id']]);
                }
                
                // Clear cart
                $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
                $stmt->execute([$user_id]);
                
                // Redirect to confirmation
                header("Location: order-confirmation.php?order_id=$order_id");
                exit;
            }
        }
    }
    
} catch (Exception $e) {
    $error = 'An error occurred. Please try again.';
    error_log("Checkout error: " . $e->getMessage());
}

require_once '../includes/header.php';
?>

<div class="container">
    <div class="checkout-page">
        <h1>Checkout</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="checkout-grid">
            <!-- ============================================================
            BILLING INFORMATION
            ============================================================ -->
            <div class="billing-section">
                <h2>Billing Information</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" value="<?php echo $user['full_name']; ?>" disabled>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" value="<?php echo $user['email']; ?>" disabled>
                    </div>
                    
                    <div class="form-group">
                        <label for="shipping_address">Shipping Address *</label>
                        <textarea id="shipping_address" name="shipping_address" rows="3" required>
                            <?php echo $user['shipping_address'] ?? ''; ?>
                        </textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="payment_method">Payment Method *</label>
                        <select id="payment_method" name="payment_method" required>
                            <option value="">Select payment method</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="paypal">PayPal</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-primary place-order">
                        <i class="fas fa-check"></i> Place Order
                    </button>
                </form>
            </div>
            
            <!-- ============================================================
            ORDER SUMMARY
            ============================================================ -->
            <div class="order-summary">
                <h2>Order Summary</h2>
                <div class="summary-items">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="summary-item">
                            <span><?php echo $item['name']; ?> × <?php echo $item['quantity']; ?></span>
                            <span><?php echo formatPrice($item['price'] * $item['quantity']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="summary-totals">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span><?php echo formatPrice($subtotal); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Tax (<?php echo TAX_RATE; ?>%)</span>
                        <span><?php echo formatPrice($tax); ?></span>
                    </div>
                    <div class="summary-row total">
                        <span><strong>Total</strong></span>
                        <span><strong><?php echo formatPrice($total); ?></strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.checkout-page {
    padding: 30px 0;
}
.checkout-page h1 {
    margin-bottom: 30px;
}
.checkout-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
}
.billing-section {
    background: white;
    padding: 30px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
}
.billing-section h2 {
    margin-bottom: 20px;
}
.billing-section input[disabled] {
    background: var(--light-bg);
    cursor: not-allowed;
}
.place-order {
    width: 100%;
    padding: 15px;
    font-size: 1.1rem;
}
.order-summary {
    background: white;
    padding: 30px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    align-self: start;
}
.order-summary h2 {
    margin-bottom: 20px;
}
.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid var(--border-color);
}
.summary-totals {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 2px solid var(--border-color);
}
.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
}
.summary-row.total {
    font-size: 1.2rem;
    padding-top: 10px;
    border-top: 1px solid var(--border-color);
}
@media (max-width: 768px) {
    .checkout-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once '../includes/footer.php'; ?>