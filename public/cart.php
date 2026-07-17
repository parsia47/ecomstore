<?php
/**
 * ================================================================
 * SHOPPING CART PAGE - E-COMMERCE STORE
 * 
 * Displays and manages shopping cart items.
 * ================================================================
 */

$page_title = 'Shopping Cart';
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php?redirect=cart.php');
    exit;
}

$user_id = getUserId();
$message = '';
$error = '';

try {
    $pdo = getDBConnection();
    
    // Handle AJAX requests
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        $response = ['success' => false, 'message' => ''];
        
        if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = (int)$_POST['product_id'];
            $quantity = (int)$_POST['quantity'];
            
            // Check if product exists
            $stmt = $pdo->prepare("SELECT id, stock_quantity FROM products WHERE id = ? AND is_active = 1");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch();
            
            if (!$product) {
                $response['message'] = 'Product not found.';
                echo json_encode($response);
                exit;
            }
            
            // Check stock
            if ($product['stock_quantity'] < $quantity) {
                $response['message'] = 'Not enough stock available.';
                echo json_encode($response);
                exit;
            }
            
            // Check if already in cart
            $stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$user_id, $product_id]);
            $cart_item = $stmt->fetch();
            
            if ($cart_item) {
                // Update quantity
                $new_quantity = $cart_item['quantity'] + $quantity;
                $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
                $stmt->execute([$new_quantity, $cart_item['id']]);
            } else {
                // Add to cart
                $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
                $stmt->execute([$user_id, $product_id, $quantity]);
            }
            
            $response['success'] = true;
            $response['cart_count'] = getCartCount();
            echo json_encode($response);
            exit;
        }
        
        if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $cart_id = (int)$_POST['cart_id'];
            $quantity = max(1, (int)$_POST['quantity']);
            
            $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
            if ($stmt->execute([$quantity, $cart_id, $user_id])) {
                $response['success'] = true;
            }
            echo json_encode($response);
            exit;
        }
        
        if ($action === 'remove' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $cart_id = (int)$_POST['cart_id'];
            $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
            if ($stmt->execute([$cart_id, $user_id])) {
                $response['success'] = true;
            }
            echo json_encode($response);
            exit;
        }
    }
    
    // Get cart items
    $stmt = $pdo->prepare("
        SELECT c.*, p.name, p.price, p.image, p.stock_quantity 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll();
    
    // Calculate totals
    $subtotal = 0;
    foreach ($cart_items as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $tax = $subtotal * (TAX_RATE / 100);
    $total = $subtotal + $tax;
    
} catch (Exception $e) {
    $cart_items = [];
    $subtotal = 0;
    $tax = 0;
    $total = 0;
    $error = 'An error occurred. Please try again.';
    error_log("Cart error: " . $e->getMessage());
}

require_once '../includes/header.php';
?>

<div class="container">
    <div class="cart-page">
        <h1>Shopping Cart</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <!-- ============================================================
        CART ITEMS
        ============================================================ -->
        <?php if (count($cart_items) > 0): ?>
            <div class="cart-items">
                <?php foreach ($cart_items as $item): ?>
                    <div class="cart-item">
                        <img src="<?php echo SITE_URL; ?>assets/images/products/<?php echo $item['image']; ?>" 
                             alt="<?php echo $item['name']; ?>" 
                             class="cart-item-image">
                        <div class="cart-item-details">
                            <h3><?php echo $item['name']; ?></h3>
                            <p class="item-price"><?php echo formatPrice($item['price']); ?></p>
                            <div class="cart-item-quantity">
                                <label>Quantity:</label>
                                <input type="number" id="qty-<?php echo $item['id']; ?>" 
                                       value="<?php echo $item['quantity']; ?>" 
                                       min="1" max="<?php echo $item['stock_quantity']; ?>">
                                <button class="btn-secondary update-cart" data-cart-id="<?php echo $item['id']; ?>">
                                    <i class="fas fa-sync"></i> Update
                                </button>
                                <button class="btn-danger remove-from-cart" data-cart-id="<?php echo $item['id']; ?>">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                        <div class="cart-item-total">
                            <?php echo formatPrice($item['price'] * $item['quantity']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- ============================================================
            CART SUMMARY
            ============================================================ -->
            <div class="cart-summary">
                <div class="cart-total">
                    <h2>Order Summary</h2>
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
                    <a href="checkout.php" class="btn-primary checkout-btn">
                        <i class="fas fa-lock"></i> Proceed to Checkout
                    </a>
                </div>
            </div>
            
        <?php else: ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart fa-4x" style="color: var(--gray-text);"></i>
                <h2>Your cart is empty</h2>
                <p>Browse our products and add items to your cart.</p>
                <a href="products.php" class="btn-primary">Start Shopping</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.cart-page {
    padding: 30px 0;
}
.cart-page h1 {
    margin-bottom: 30px;
}
.cart-item {
    display: flex;
    gap: 20px;
    padding: 20px;
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    margin-bottom: 15px;
    align-items: center;
}
.cart-item-image {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: var(--border-radius);
}
.cart-item-details {
    flex: 1;
}
.cart-item-details h3 {
    margin-bottom: 5px;
}
.item-price {
    color: var(--primary-color);
    font-weight: bold;
}
.cart-item-quantity {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-top: 10px;
}
.cart-item-quantity input {
    width: 60px;
    padding: 5px;
    text-align: center;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
}
.cart-item-total {
    font-size: 1.2rem;
    font-weight: bold;
    color: var(--primary-color);
    min-width: 100px;
    text-align: right;
}
.cart-summary {
    margin-top: 30px;
}
.cart-total {
    background: white;
    padding: 30px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    max-width: 400px;
    margin-left: auto;
}
.cart-total h2 {
    margin-bottom: 20px;
}
.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid var(--border-color);
}
.summary-row.total {
    border-bottom: none;
    padding-top: 15px;
    font-size: 1.2rem;
}
.checkout-btn {
    width: 100%;
    margin-top: 20px;
    padding: 15px;
}
.empty-cart {
    text-align: center;
    padding: 60px 0;
}
.empty-cart i {
    margin-bottom: 20px;
}
.empty-cart h2 {
    margin-bottom: 10px;
}
.empty-cart p {
    color: var(--gray-text);
    margin-bottom: 20px;
}
@media (max-width: 768px) {
    .cart-item {
        flex-direction: column;
        text-align: center;
    }
    .cart-item-quantity {
        flex-wrap: wrap;
        justify-content: center;
    }
    .cart-item-total {
        text-align: center;
    }
    .cart-total {
        max-width: 100%;
        margin: 0;
    }
}
</style>

<?php require_once '../includes/footer.php'; ?>