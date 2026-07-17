<?php
/**
 * ================================================================
 * PRODUCTS PAGE - E-COMMERCE STORE
 * 
 * Displays all products with search and filter functionality.
 * ================================================================
 */

$page_title = 'Products';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

try {
    $pdo = getDBConnection();
    
    // Get filter parameters
    $search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
    $category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;
    $sort = isset($_GET['sort']) ? sanitize($_GET['sort']) : 'newest';
    $min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
    $max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 99999;
    
    // Build query
    $query = "
        SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.is_active = 1
    ";
    $params = [];
    
    if ($search) {
        $query .= " AND (p.name LIKE ? OR p.description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    if ($category_id > 0) {
        $query .= " AND p.category_id = ?";
        $params[] = $category_id;
    }
    
    if ($min_price > 0) {
        $query .= " AND p.price >= ?";
        $params[] = $min_price;
    }
    
    if ($max_price < 99999) {
        $query .= " AND p.price <= ?";
        $params[] = $max_price;
    }
    
    // Sorting
    switch ($sort) {
        case 'price_low':
            $query .= " ORDER BY p.price ASC";
            break;
        case 'price_high':
            $query .= " ORDER BY p.price DESC";
            break;
        case 'rating':
            $query .= " ORDER BY p.rating DESC";
            break;
        case 'popular':
            $query .= " ORDER BY p.total_reviews DESC";
            break;
        default:
            $query .= " ORDER BY p.created_at DESC";
    }
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $products = $stmt->fetchAll();
    
    // Get categories for filter
    $categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
    
} catch (Exception $e) {
    $products = [];
    $categories = [];
    error_log("Products page error: " . $e->getMessage());
}
?>

<div class="container">
    <div class="products-page">
        <h1>All Products</h1>
        <p class="results-count"><?php echo count($products); ?> products found</p>
        
        <!-- ============================================================
        FILTERS
        ============================================================ -->
        <div class="filters-section">
            <form method="GET" action="" class="filter-form">
                <div class="filter-group">
                    <label>Category</label>
                    <select name="category">
                        <option value="0">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" 
                                    <?php echo $category_id == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo $cat['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>Sort By</label>
                    <select name="sort">
                        <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest</option>
                        <option value="price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                        <option value="rating" <?php echo $sort == 'rating' ? 'selected' : ''; ?>>Highest Rated</option>
                        <option value="popular" <?php echo $sort == 'popular' ? 'selected' : ''; ?>>Most Popular</option>
                    </select>
                </div>
                
                <div class="filter-group price-range">
                    <label>Price Range</label>
                    <div class="price-inputs">
                        <input type="number" name="min_price" placeholder="Min" value="<?php echo $min_price ?: ''; ?>">
                        <span>-</span>
                        <input type="number" name="max_price" placeholder="Max" value="<?php echo $max_price < 99999 ? $max_price : ''; ?>">
                    </div>
                </div>
                
                <button type="submit" class="btn-primary">Apply Filters</button>
                
                <?php if ($search || $category_id || $min_price || $max_price || $sort != 'newest'): ?>
                    <a href="products.php" class="btn-secondary">Clear All</a>
                <?php endif; ?>
            </form>
        </div>
        
        <!-- ============================================================
        PRODUCTS GRID
        ============================================================ -->
        <?php if (count($products) > 0): ?>
            <div class="grid-4 products-grid">
                <?php foreach ($products as $product): ?>
                    <div class="card product-card" data-category="<?php echo $product['category_id']; ?>">
                        <img src="<?php echo SITE_URL; ?>assets/images/products/<?php echo $product['image']; ?>" 
                             alt="<?php echo $product['name']; ?>" 
                             class="product-image"
                             loading="lazy">
                        <div class="product-info">
                            <h3><?php echo $product['name']; ?></h3>
                            <p class="category-label"><?php echo $product['category_name']; ?></p>
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
                            <div class="product-actions">
                                <a href="product-detail.php?id=<?php echo $product['id']; ?>" class="btn-secondary">View</a>
                                <button class="btn-primary add-to-cart" data-product-id="<?php echo $product['id']; ?>">
                                    <i class="fas fa-shopping-cart"></i> Add
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <p>No products found matching your criteria.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.products-page {
    padding: 30px 0;
}
.products-page h1 {
    margin-bottom: 5px;
}
.results-count {
    color: var(--gray-text);
    margin-bottom: 20px;
}
.filters-section {
    background: white;
    padding: 20px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    margin-bottom: 30px;
}
.filter-form {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: flex-end;
}
.filter-group {
    flex: 1;
    min-width: 150px;
}
.filter-group label {
    display: block;
    font-weight: 500;
    font-size: 0.9rem;
    margin-bottom: 5px;
}
.filter-group select,
.filter-group input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
}
.price-inputs {
    display: flex;
    gap: 5px;
    align-items: center;
}
.price-inputs input {
    width: 45%;
    padding: 8px 10px;
}
.price-inputs span {
    color: var(--gray-text);
}
.product-card .product-actions {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}
.product-card .product-actions a,
.product-card .product-actions button {
    flex: 1;
}
.category-label {
    color: var(--gray-text);
    font-size: 0.8rem;
    margin-bottom: 5px;
}
@media (max-width: 768px) {
    .filter-form {
        flex-direction: column;
    }
    .filter-group {
        min-width: 100%;
    }
    .product-card .product-actions {
        flex-direction: column;
    }
}
</style>

<?php require_once '../includes/footer.php'; ?>