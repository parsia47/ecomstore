<?php
/**
 * ================================================================
 * SHOPPING GUIDE HELP - E-COMMERCE STORE
 * 
 * Detailed guide on how to shop on the platform.
 * ================================================================
 */

$page_title = 'Shopping Guide';
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
require_once '../../includes/header.php';
?>

<div class="container">
    <div class="help-article">
        <h1>Shopping Guide</h1>
        
        <div class="article-content">
            <!-- ============================================================
            HOW TO SHOP
            ============================================================ -->
            <section>
                <h2>How to Shop on <?php echo SITE_NAME; ?></h2>
                <p>Follow these steps to make a purchase:</p>
                <ol>
                    <li><strong>Browse Products:</strong> Use the <a href="../products.php">Products page</a> to find items you like</li>
                    <li><strong>View Product Details:</strong> Click on a product to see more information</li>
                    <li><strong>Select Options:</strong> Choose size, color, or other options if available</li>
                    <li><strong>Add to Cart:</strong> Click "Add to Cart" to save the item</li>
                    <li><strong>Review Cart:</strong> Go to your cart to check all items</li>
                    <li><strong>Checkout:</strong> Enter shipping details and payment method</li>
                    <li><strong>Confirm Order:</strong> Review and place your order</li>
                </ol>
                
                <div class="video-container">
                    <video controls style="width: 100%; max-width: 600px; border-radius: 8px;">
                        <source src="<?php echo SITE_URL; ?>assets/videos/checkout-tutorial.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </section>
            
            <!-- ============================================================
            MANAGING YOUR CART
            ============================================================ -->
            <section>
                <h2>Managing Your Cart</h2>
                
                <h3>Add Items</h3>
                <ul>
                    <li>Browse products and click "Add to Cart"</li>
                    <li>Adjust quantity before adding</li>
                    <li>Cart badge shows total items</li>
                </ul>
                
                <h3>Update Quantities</h3>
                <ul>
                    <li>Go to your cart</li>
                    <li>Change the quantity using the input field</li>
                    <li>Click "Update" to save changes</li>
                </ul>
                
                <h3>Remove Items</h3>
                <ul>
                    <li>Go to your cart</li>
                    <li>Click the "Remove" button on any item</li>
                    <li>Confirm to remove the item</li>
                </ul>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Items in your cart are saved until you check out or remove them.
                </div>
            </section>
            
            <!-- ============================================================
            CHECKOUT PROCESS
            ============================================================ -->
            <section>
                <h2>Checkout Process</h2>
                <p>When you're ready to complete your purchase:</p>
                <ol>
                    <li>Click "Proceed to Checkout" from your cart</li>
                    <li>Enter your shipping address</li>
                    <li>Select your payment method</li>
                    <li>Review the order summary</li>
                    <li>Click "Place Order" to complete</li>
                </ol>
                
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> 
                    You'll receive an order confirmation email with tracking information.
                </div>
            </section>
            
            <!-- ============================================================
            SHIPPING & RETURNS
            ============================================================ -->
            <section>
                <h2>Shipping & Returns</h2>
                
                <h3>Shipping Information</h3>
                <ul>
                    <li><strong>Standard Shipping:</strong> 3-5 business days</li>
                    <li><strong>Express Shipping:</strong> 1-2 business days</li>
                    <li><strong>Free Shipping:</strong> On orders over $100</li>
                    <li><strong>International:</strong> Available to most countries</li>
                </ul>
                
                <h3>Return Policy</h3>
                <ul>
                    <li><strong>Return Window:</strong> 30 days from delivery</li>
                    <li><strong>Condition:</strong> Items must be in original condition</li>
                    <li><strong>Refund:</strong> Full refund to original payment method</li>
                </ul>
            </section>
        </div>
        
        <!-- ============================================================
        NAVIGATION
        ============================================================ -->
        <div class="article-navigation">
            <a href="getting-started.php" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Previous: Getting Started
            </a>
            <a href="account-management.php" class="btn-primary">
                Next: Account Management <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<style>
.help-article h1 {
    margin: 30px 0;
}
.article-content section {
    background: white;
    padding: 30px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    margin-bottom: 30px;
}
.article-content h2 {
    color: var(--secondary-color);
    margin-bottom: 15px;
}
.article-content h3 {
    color: var(--secondary-color);
    margin: 20px 0 10px;
}
.article-content ol, .article-content ul {
    padding-left: 20px;
}
.article-content li {
    margin-bottom: 8px;
}
.article-content a {
    color: var(--primary-color);
    text-decoration: none;
}
.article-content a:hover {
    text-decoration: underline;
}
.video-container {
    text-align: center;
    margin: 20px 0;
}
.article-navigation {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    margin-top: 30px;
    flex-wrap: wrap;
}
@media (max-width: 768px) {
    .article-navigation {
        flex-direction: column;
    }
    .article-navigation a {
        width: 100%;
        text-align: center;
    }
}
</style>

<?php require_once '../../includes/footer.php'; ?>