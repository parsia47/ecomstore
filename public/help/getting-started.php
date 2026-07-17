<?php
/**
 * ================================================================
 * GETTING STARTED HELP - E-COMMERCE STORE
 * 
 * Step-by-step guide for new users to get started.
 * ================================================================
 */

$page_title = 'Getting Started';
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
require_once '../../includes/header.php';
?>

<div class="container">
    <div class="help-article">
        <h1>Getting Started with <?php echo SITE_NAME; ?></h1>
        
        <div class="article-content">
            <!-- ============================================================
            STEP 1: CREATE ACCOUNT
            ============================================================ -->
            <section>
                <h2>Step 1: Create Your Account</h2>
                <ol>
                    <li>Go to the <a href="../register.php">Registration Page</a></li>
                    <li>Fill in your details:
                        <ul>
                            <li>Full Name</li>
                            <li>Username</li>
                            <li>Email Address</li>
                            <li>Password (minimum 8 characters)</li>
                        </ul>
                    </li>
                    <li>Click the "Create Account" button</li>
                    <li>You'll be automatically logged in</li>
                </ol>
                
                <!-- Video Tutorial -->
                <div class="video-container">
                    <video controls style="width: 100%; max-width: 600px; border-radius: 8px;">
                        <source src="<?php echo SITE_URL; ?>assets/videos/shopping-tutorial.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </section>
            
            <!-- ============================================================
            STEP 2: BROWSE PRODUCTS
            ============================================================ -->
            <section>
                <h2>Step 2: Browse Products</h2>
                <ol>
                    <li>Go to the <a href="../products.php">Products Page</a></li>
                    <li>Browse through our product catalogue</li>
                    <li>Use filters to narrow down your search:
                        <ul>
                            <li>Search by name</li>
                            <li>Filter by category</li>
                            <li>Sort by price or rating</li>
                        </ul>
                    </li>
                    <li>Click on a product to view details</li>
                </ol>
            </section>
            
            <!-- ============================================================
            STEP 3: ADD TO CART
            ============================================================ -->
            <section>
                <h2>Step 3: Add to Cart</h2>
                <ol>
                    <li>On the product detail page, select quantity</li>
                    <li>Choose any options (size, color, etc.)</li>
                    <li>Click "Add to Cart"</li>
                    <li>View your cart to see all items</li>
                </ol>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    You can update quantities or remove items from your cart anytime.
                </div>
            </section>
            
            <!-- ============================================================
            STEP 4: CHECKOUT
            ============================================================ -->
            <section>
                <h2>Step 4: Checkout</h2>
                <ol>
                    <li>Go to your <a href="../cart.php">Cart</a></li>
                    <li>Review your items and total</li>
                    <li>Click "Proceed to Checkout"</li>
                    <li>Enter your shipping address</li>
                    <li>Select payment method</li>
                    <li>Click "Place Order" to complete</li>
                </ol>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> 
                    You'll receive an order confirmation with your order number.
                </div>
            </section>
        </div>
        
        <!-- ============================================================
        NAVIGATION
        ============================================================ -->
        <div class="article-navigation">
            <a href="index.php" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Help Center
            </a>
            <a href="shopping-guide.php" class="btn-primary">
                Next: Shopping Guide <i class="fas fa-arrow-right"></i>
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