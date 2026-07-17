<?php
/**
 * ================================================================
 * ABOUT PAGE - E-COMMERCE STORE
 * 
 * This page displays information about the company,
 * our mission, team, and business case.
 * ================================================================
 */

// Set page title and include required files
$page_title = 'About Us';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';
?>

<div class="container">
    <div class="about-page">
        <!-- Page Header -->
        <h1>About <?php echo SITE_NAME; ?></h1>
        
        <div class="about-content">
            <!-- ============================================================
            BUSINESS CASE SECTION
            ============================================================ -->
            <div class="about-section">
                <h2>Our Business Case</h2>
                <p>
                    <?php echo SITE_NAME; ?> is a comprehensive e-commerce platform 
                    that offers a wide range of products across multiple categories 
                    including Electronics, Books, Clothing, Home & Garden, Sports, 
                    and Toys.
                </p>
                <p>
                    Our catalogue features <strong>over 27 products</strong> with 
                    various options and variations. We provide a complete online 
                    shopping experience with:
                </p>
                <ul>
                    <li><strong>Product Catalogue:</strong> 27+ products across 6 categories</li>
                    <li><strong>Shopping Cart:</strong> Add, remove, and update items</li>
                    <li><strong>Secure Checkout:</strong> Payment processing and order confirmation</li>
                    <li><strong>Order Management:</strong> Track orders and view history</li>
                    <li><strong>User Accounts:</strong> Customer registration and login</li>
                    <li><strong>Product Reviews:</strong> Rate and review products</li>
                    <li><strong>Admin Panel:</strong> Manage products, orders, and users</li>
                </ul>
            </div>
            
            <!-- ============================================================
            MISSION SECTION
            ============================================================ -->
            <div class="about-section">
                <h2>Our Mission</h2>
                <p>
                    At <?php echo SITE_NAME; ?>, our mission is to provide customers 
                    with a seamless online shopping experience. We believe in:
                </p>
                <ul>
                    <li><strong>Quality Products:</strong> Curating the best products for our customers</li>
                    <li><strong>Competitive Pricing:</strong> Offering the best value for money</li>
                    <li><strong>Fast Shipping:</strong> Getting orders to customers quickly</li>
                    <li><strong>Excellent Service:</strong> Providing top-notch customer support</li>
                </ul>
            </div>
            
            <!-- ============================================================
            WHAT WE OFFER
            ============================================================ -->
            <div class="about-section">
                <h2>What We Offer</h2>
                <div class="grid-3">
                    <div class="card">
                        <i class="fas fa-laptop fa-2x" style="color: var(--primary-color);"></i>
                        <h3>Electronics</h3>
                        <p>Latest gadgets, laptops, smartphones, and accessories.</p>
                    </div>
                    <div class="card">
                        <i class="fas fa-book fa-2x" style="color: var(--primary-color);"></i>
                        <h3>Books</h3>
                        <p>Best-selling books, e-books, and educational materials.</p>
                    </div>
                    <div class="card">
                        <i class="fas fa-tshirt fa-2x" style="color: var(--primary-color);"></i>
                        <h3>Clothing</h3>
                        <p>Fashion apparel, shoes, and accessories for all.</p>
                    </div>
                    <div class="card">
                        <i class="fas fa-home fa-2x" style="color: var(--primary-color);"></i>
                        <h3>Home & Garden</h3>
                        <p>Home decor, furniture, and garden supplies.</p>
                    </div>
                    <div class="card">
                        <i class="fas fa-football fa-2x" style="color: var(--primary-color);"></i>
                        <h3>Sports</h3>
                        <p>Sports equipment and gear for all activities.</p>
                    </div>
                    <div class="card">
                        <i class="fas fa-gamepad fa-2x" style="color: var(--primary-color);"></i>
                        <h3>Toys</h3>
                        <p>Toys and games for kids of all ages.</p>
                    </div>
                </div>
            </div>
            
            <!-- ============================================================
            VIDEO SECTION
            ============================================================ -->
            <div class="about-section">
                <h2>Watch Our Story</h2>
                <div class="video-container">
                    <video controls 
                           style="width: 100%; max-width: 800px; border-radius: 8px;"
                           poster="<?php echo SITE_URL; ?>assets/images/hero-bg.jpg">
                        <source src="<?php echo SITE_URL; ?>assets/videos/intro.mp4" type="video/mp4">
                        <source src="<?php echo SITE_URL; ?>assets/videos/intro.ogv" type="video/ogg">
                        Your browser does not support the video tag. Please update your browser.
                    </video>
                    <p class="video-caption">Learn more about our story and vision</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.about-page h1 {
    margin: 30px 0 40px;
    text-align: center;
}
.about-section {
    margin-bottom: 40px;
    background: white;
    padding: 30px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
}
.about-section h2 {
    color: var(--secondary-color);
    margin-bottom: 15px;
}
.about-section p {
    line-height: 1.8;
    margin-bottom: 15px;
}
.about-section ul {
    padding-left: 20px;
}
.about-section ul li {
    margin-bottom: 8px;
    line-height: 1.6;
}
.video-container {
    text-align: center;
}
.video-caption {
    margin-top: 10px;
    color: var(--gray-text);
    font-style: italic;
}
</style>

<?php require_once '../includes/footer.php'; ?>