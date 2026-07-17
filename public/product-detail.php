<?php
/**
 * ================================================================
 * PRODUCT DETAIL PAGE - E-COMMERCE STORE
 * 
 * Displays full product information with options and reviews.
 * ================================================================
 */

$page_title = 'Product Detail';
require_once '../includes/config.php';
require_once '../includes/functions.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$product_id) {
    header('Location: products.php');
    exit;
}

try {
    $pdo = getDBConnection();
    
    // Get product details
    $stmt = $pdo->prepare("
        SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.id = ? AND p.is_active = 1
    ");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    
    if (!$product) {
        header('Location: products.php');
        exit;
    }
    
    // Get product options
    $stmt = $pdo->prepare("SELECT * FROM product_options WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $options = $stmt->fetchAll();
    
    // Get reviews
    $stmt = $pdo->prepare("
        SELECT r.*, u.full_name 
        FROM reviews r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.product_id = ? 
        ORDER BY r.created_at DESC 
        LIMIT 5
    ");
    $stmt->execute([$product_id]);
    $reviews = $stmt->fetchAll();
    
    // Get related products (same category)
    $stmt = $pdo->prepare("
        SELECT * FROM products 
        WHERE category_id = ? AND id != ? AND is_active = 1 
        ORDER BY RAND() 
        LIMIT 4
    ");
    $stmt->execute([$product['category_id'], $product_id]);
    $related_products = $stmt->fetchAll();
    
} catch (Exception $e) {
    error_log("Product detail error: " . $e->getMessage());
    header('Location: products.php');
    exit;
}

require_once '../includes/header.php';
?>

<div class="container">
    <div class="product-detail">
        <!-- ============================================================
        PRODUCT MAIN
        ============================================================ -->
        <div class="product-main">
            <div class="product-image-section">
                <img src="<?php echo SITE_URL; ?>assets/images/products/<?php echo $product['image']; ?>" 
                     alt="<?php echo $product['name']; ?>" 
                     class="product-main-image">
            </div>
            
            <div class="product-info-section">
                <h1><?php echo $product['name']; ?></h1>
                <p class="category">Category: <?php echo $product['category_name']; ?></p>
                
                <div class="rating-section">
                    <?php echo displayStars($product['rating']); ?>
                    <span class="rating-count"><?php echo $product['total_reviews']; ?> reviews</span>
                </div>
                
                <div class="price-section">
                    <span class="current-price"><?php echo formatPrice($product['price']); ?></span>
                    <?php if ($product['sale_price']): ?>
                        <span class="original-price"><?php echo formatPrice($product['sale_price']); ?></span>
                        <span class="savings">Save <?php echo formatPrice($product['sale_price'] - $product['price']); ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="stock-status">
                    <span class="badge badge-<?php echo $product['stock_quantity'] > 0 ? 'success' : 'danger'; ?>">
                        <?php echo $product['stock_quantity'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                    </span>
                    <span class="stock-count"><?php echo $product['stock_quantity']; ?> units available</span>
                </div>
                
                <div class="description">
                    <h3>Description</h3>
                    <p><?php echo $product['description']; ?></p>
                </div>
                
                <?php if ($options): ?>
                    <div class="options-section">
                        <h3>Options</h3>
                        <?php foreach ($options as $option): ?>
                            <div class="option-group">
                                <label><?php echo $option['option_name']; ?></label>
                                <select name="option_<?php echo $option['id']; ?>" class="product-option">
                                    <option value="<?php echo $option['id']; ?>">
                                        <?php echo $option['option_value']; ?> 
                                        <?php if ($option['price_adjustment'] > 0): ?>
                                            (+<?php echo formatPrice($option['price_adjustment']); ?>)
                                        <?php endif; ?>
                                    </option>
                                </select>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <div class="quantity-section">
                    <label>Quantity</label>
                    <input type="number" id="quantity-<?php echo $product['id']; ?>" 
                           value="1" min="1" max="<?php echo $product['stock_quantity']; ?>">
                </div>
                
                <div class="action-buttons">
                    <button class="btn-primary add-to-cart" data-product-id="<?php echo $product['id']; ?>">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                    <button class="btn-secondary">
                        <i class="fas fa-heart"></i> Wishlist
                    </button>
                </div>
            </div>
        </div>
        
        <!-- ============================================================
        REVIEWS SECTION
        ============================================================ -->
        <div class="reviews-section">
            <h2>Customer Reviews</h2>
            
            <?php if ($reviews): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <strong><?php echo $review['full_name']; ?></strong>
                            <span class="review-rating"><?php echo displayStars($review['rating']); ?></span>
                            <span class="review-date"><?php echo formatDate($review['created_at']); ?></span>
                        </div>
                        <p><?php echo $review['review']; ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info">No reviews yet. Be the first to review!</div>
            <?php endif; ?>
            
            <?php if (isLoggedIn()): ?>
                <div class="write-review">
                    <h3>Write a Review</h3>
                    <form action="process-review.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <div class="form-group">
                            <label>Rating</label>
                            <div class="rating-container">
                                <input type="radio" name="rating" value="5" id="star5">
                                <label for="star5" class="star">★</label>
                                <input type="radio" name="rating" value="4" id="star4">
                                <label for="star4" class="star">★</label>
                                <input type="radio" name="rating" value="3" id="star3">
                                <label for="star3" class="star">★</label>
                                <input type="radio" name="rating" value="2" id="star2">
                                <label for="star2" class="star">★</label>
                                <input type="radio" name="rating" value="1" id="star1">
                                <label for="star1" class="star">★</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="review">Your Review</label>
                            <textarea id="review" name="review" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn-primary">Submit Review</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- ============================================================
        RELATED PRODUCTS
        ============================================================ -->
        <?php if ($related_products): ?>
            <div class="related-products">
                <h2>Related Products</h2>
                <div class="grid-4">
                    <?php foreach ($related_products as $rel_product): ?>
                        <div class="card product-card">
                            <img src="<?php echo SITE_URL; ?>assets/images/products/<?php echo $rel_product['image']; ?>" 
                                 alt="<?php echo $rel_product['name']; ?>" 
                                 class="product-image"
                                 loading="lazy">
                            <h3><?php echo $rel_product['name']; ?></h3>
                            <p class="price"><?php echo formatPrice($rel_product['price']); ?></p>
                            <a href="product-detail.php?id=<?php echo $rel_product['id']; ?>" class="btn-secondary">View</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.product-detail {
    padding: 30px 0;
}
.product-main {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-bottom: 40px;
}
.product-main-image {
    width: 100%;
    max-height: 500px;
    object-fit: contain;
    border-radius: var(--border-radius);
    background: white;
    padding: 20px;
}
.product-info-section h1 {
    margin-bottom: 5px;
}
.product-info-section .category {
    color: var(--gray-text);
    margin-bottom: 15px;
}
.rating-section {
    margin-bottom: 15px;
}
.rating-section .rating-count {
    color: var(--gray-text);
    margin-left: 10px;
}
.price-section {
    margin-bottom: 15px;
}
.current-price {
    font-size: 2rem;
    font-weight: bold;
    color: var(--primary-color);
}
.original-price {
    text-decoration: line-through;
    color: var(--gray-text);
    font-size: 1.2rem;
    margin-left: 15px;
}
.savings {
    display: inline-block;
    background: var(--success-color);
    color: white;
    padding: 2px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
    margin-left: 10px;
}
.stock-status {
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 15px;
}
.quantity-section {
    margin: 15px 0;
}
.quantity-section input {
    width: 80px;
    padding: 8px;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
}
.action-buttons {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}
.action-buttons button {
    flex: 1;
}
.reviews-section {
    background: white;
    padding: 30px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    margin-bottom: 30px;
}
.reviews-section h2 {
    margin-bottom: 20px;
}
.review-card {
    padding: 15px 0;
    border-bottom: 1px solid var(--border-color);
}
.review-card:last-child {
    border-bottom: none;
}
.review-header {
    display: flex;
    gap: 15px;
    margin-bottom: 10px;
    flex-wrap: wrap;
}
.write-review {
    margin-top: 30px;
    padding-top: 30px;
    border-top: 1px solid var(--border-color);
}
.write-review h3 {
    margin-bottom: 15px;
}
.rating-container {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}
.rating-container input {
    display: none;
}
.rating-container .star {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
}
.rating-container .star:hover,
.rating-container .star:hover ~ .star,
.rating-container input:checked ~ .star {
    color: #f1c40f;
}
.related-products {
    margin-top: 30px;
}
.related-products h2 {
    margin-bottom: 20px;
}
@media (max-width: 768px) {
    .product-main {
        grid-template-columns: 1fr;
    }
    .action-buttons {
        flex-direction: column;
    }
    .review-header {
        flex-direction: column;
        gap: 5px;
    }
}
</style>

<?php require_once '../includes/footer.php'; ?>