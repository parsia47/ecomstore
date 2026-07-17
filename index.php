<?php
/**
 * ================================================================
 * HOMEPAGE - E-COMMERCE STORE
 * 
 * Displays featured products, categories, and new arrivals.
 * This is the main landing page of the store.
 * ================================================================
 */

$page_title = 'Home';
require_once 'includes/config.php';        // ← CORRECT for root
require_once 'includes/functions.php';     // ← CORRECT for root
require_once 'includes/header.php';        // ← CORRECT for root

try {
    $pdo = getDBConnection();
    
    // Get featured products (is_featured = 1)
    $stmt = $pdo->query("
        SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.is_featured = 1 AND p.is_active = 1 
        ORDER BY p.created_at DESC 
        LIMIT 8
    ");
    $featured_products = $stmt->fetchAll();
    
    // Get all categories for display
    $categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
    
    // Get new arrivals (most recent products)
    $stmt = $pdo->query("
        SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.is_active = 1 
        ORDER BY p.created_at DESC 
        LIMIT 4
    ");
    $new_arrivals = $stmt->fetchAll();
    
} catch (Exception $e) {
    $featured_products = [];
    $categories = [];
    $new_arrivals = [];
    error_log("Homepage error: " . $e->getMessage());
}
?>

<!-- ============================================================
HERO SECTION
============================================================ -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1>Welcome to <?php echo SITE_NAME; ?></h1>
            <p>Discover thousands of products at unbeatable prices. Shop the best deals today!</p>
            
            <!-- Search Form -->
            <form id="product-search" class="search-form">
                <input type="text" id="search-input" placeholder="Search for products..." required>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>
        </div>
    </div>
</section>

<!-- ============================================================
CATEGORIES SECTION
============================================================ -->
<section class="categories-section">
    <div class="container">
        <h2>Shop by Category</h2>
        <div class="grid-4">
            <?php foreach ($categories as $category): ?>
                <a href="products.php?category=<?php echo $category['id']; ?>" class="category-card">
                    <i class="fas <?php echo $category['icon']; ?> fa-3x"></i>
                    <h3><?php echo $category['name']; ?></h3>
                    <p><?php echo $category['description']; ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
FEATURED PRODUCTS
============================================================ -->
<section class="featured-section">
    <div class="container">
        <h2>Featured Products</h2>
        <div class="grid-4">
            <?php foreach ($featured_products as $product): ?>
                <div class="card product-card" data-category="<?php echo $product['category_id']; ?>">
                    <img src="<?php echo SITE_URL; ?>assets/images/products/<?php echo $product['image']; ?>" 
                         alt="<?php echo $product['name']; ?>" 
                         class="product-image"
                         loading="lazy">
                    <h3><?php echo $product['name']; ?></h3>
                    <div class="rating">
                        <?php echo displayStars($product['rating']); ?>
                        <span>(<?php echo $product['total_reviews']; ?>)</span>
                    </div>
                    <p class="price">
                        <?php echo formatPrice($product['price']); ?>
                        <?php if ($product['sale_price']): ?>
                            <span class="sale-price"><?php echo formatPrice($product['sale_price']); ?></span>
                        <?php endif; ?>
                    </p>
                    <a href="public/product-detail.php?id=<?php echo $product['id']; ?>" class="btn-primary">View Product</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
NEW ARRIVALS
============================================================ -->
<section class="new-arrivals-section">
    <div class="container">
        <h2>New Arrivals</h2>
        <div class="grid-4">
            <?php foreach ($new_arrivals as $product): ?>
                <div class="card product-card">
                    <img src="<?php echo SITE_URL; ?>assets/images/products/<?php echo $product['image']; ?>" 
                         alt="<?php echo $product['name']; ?>" 
                         class="product-image"
                         loading="lazy">
                    <h3><?php echo $product['name']; ?></h3>
                    <div class="rating">
                        <?php echo displayStars($product['rating']); ?>
                        <span>(<?php echo $product['total_reviews']; ?>)</span>
                    </div>
                    <p class="price">
                        <?php echo formatPrice($product['price']); ?>
                        <?php if ($product['sale_price']): ?>
                            <span class="sale-price"><?php echo formatPrice($product['sale_price']); ?></span>
                        <?php endif; ?>
                    </p>
                   <a href="public/product-detail.php?id=<?php echo $product['id']; ?>" class="btn-primary">View Product</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
.categories-section {
    padding: 60px 0;
    background: white;
}
.categories-section h2 {
    text-align: center;
    margin-bottom: 40px;
}
.category-card {
    text-align: center;
    padding: 30px;
    background: var(--light-bg);
    border-radius: var(--border-radius);
    text-decoration: none;
    color: var(--dark-text);
    transition: all var(--transition-speed) ease;
}
.category-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--box-shadow-hover);
}
.category-card i {
    color: var(--primary-color);
    margin-bottom: 15px;
}
.category-card h3 {
    margin-bottom: 5px;
}
.category-card p {
    color: var(--gray-text);
    font-size: 0.9rem;
}
.featured-section, .new-arrivals-section {
    padding: 60px 0;
}
.featured-section h2, .new-arrivals-section h2 {
    text-align: center;
    margin-bottom: 40px;
}
</style>

<?php require_once 'includes/footer.php';        // ← CORRECT?>