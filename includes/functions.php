<?php
/**
 * ================================================================
 * FUNCTIONS FILE - E-COMMERCE STORE
 * 
 * This file contains all helper functions used throughout the site.
 * Includes authentication, cart, product, and formatting functions.
 * ================================================================
 */

// ================================================================
// SECTION 1: AUTHENTICATION FUNCTIONS
// ================================================================

/**
 * Check if a user is currently logged in
 * 
 * @return bool True if user is logged in, false otherwise
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if the logged-in user is an admin
 * 
 * @return bool True if user is admin, false otherwise
 */
function isAdmin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin';
}

/**
 * Check if the logged-in user is a customer
 * 
 * @return bool True if user is customer, false otherwise
 */
function isCustomer() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'customer';
}

/**
 * Get the current user's ID
 * 
 * @return int|null User ID if logged in, null otherwise
 */
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get the current user's type
 * 
 * @return string|null User type (customer/admin) if logged in, null otherwise
 */
function getUserType() {
    return $_SESSION['user_type'] ?? null;
}

// ================================================================
// SECTION 2: TEMPLATE FUNCTIONS
// ================================================================

/**
 * Get the currently active template from the database
 * 
 * @return string Template name (template1, template2, or template3)
 */
function getActiveTemplate() {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->query("SELECT setting_value FROM site_settings WHERE setting_key = 'active_template'");
        $result = $stmt->fetch();
        return $result ? $result['setting_value'] : 'template1';
    } catch (Exception $e) {
        return 'template1'; // Default fallback
    }
}

// ================================================================
// SECTION 3: SANITIZATION FUNCTIONS
// ================================================================

/**
 * Sanitize user input to prevent XSS attacks
 * 
 * @param string $input Raw user input
 * @return string Sanitized HTML-safe string
 */
function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

// ================================================================
// SECTION 4: REDIRECTION FUNCTIONS
// ================================================================

/**
 * Redirect to a specific URL within the site
 * 
 * @param string $url URL to redirect to (relative to SITE_URL)
 */
function redirect($url) {
    header("Location: " . SITE_URL . $url);
    exit;
}

// ================================================================
// SECTION 5: SECURITY FUNCTIONS
// ================================================================

/**
 * Generate a CSRF token for form security
 * 
 * @return string CSRF token
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify a CSRF token
 * 
 * @param string $token Token to verify
 * @return bool True if token is valid
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// ================================================================
// SECTION 6: CART FUNCTIONS
// ================================================================

/**
 * Get the total number of items in the user's cart
 * 
 * @return int Total cart count
 */
function getCartCount() {
    if (!isLoggedIn()) return 0;
    
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
        $stmt->execute([getUserId()]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    } catch (Exception $e) {
        return 0;
    }
}

/**
 * Get the total price of all items in the user's cart
 * 
 * @return float Total cart value
 */
function getCartTotal() {
    if (!isLoggedIn()) return 0;
    
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("
            SELECT SUM(c.quantity * p.price) as total 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = ?
        ");
        $stmt->execute([getUserId()]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    } catch (Exception $e) {
        return 0;
    }
}

// ================================================================
// SECTION 7: FORMATTING FUNCTIONS
// ================================================================

/**
 * Format a price with currency symbol
 * 
 * @param float $price Price to format
 * @return string Formatted price (e.g., "$19.99")
 */
function formatPrice($price) {
    return CURRENCY_SYMBOL . number_format($price, 2);
}

/**
 * Format a date for display
 * 
 * @param string $date Date string
 * @return string Formatted date (e.g., "Jan 15, 2024")
 */
function formatDate($date) {
    return date('M d, Y', strtotime($date));
}

/**
 * Format a time for display
 * 
 * @param string $time Time string
 * @return string Formatted time (e.g., "2:30 PM")
 */
function formatTime($time) {
    return date('g:i A', strtotime($time));
}

// ================================================================
// SECTION 8: PRODUCT FUNCTIONS
// ================================================================

/**
 * Get product rating and review count
 * 
 * @param int $product_id Product ID
 * @return array Array with 'rating' and 'total' keys
 */
function getProductRating($product_id) {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("
            SELECT AVG(rating) as avg_rating, COUNT(*) as total 
            FROM reviews 
            WHERE product_id = ? AND rating IS NOT NULL
        ");
        $stmt->execute([$product_id]);
        $result = $stmt->fetch();
        return [
            'rating' => round($result['avg_rating'] ?? 0, 1),
            'total' => $result['total'] ?? 0
        ];
    } catch (Exception $e) {
        return ['rating' => 0, 'total' => 0];
    }
}

/**
 * Display star rating HTML
 * 
 * @param float $rating Rating value (0-5)
 * @return string HTML for star display
 */
function displayStars($rating) {
    $output = '<span class="stars">';
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5;
    
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $fullStars) {
            $output .= '★';
        } elseif ($halfStar && $i == $fullStars + 1) {
            $output .= '☆';
        } else {
            $output .= '☆';
        }
    }
    $output .= '</span>';
    return $output;
}

// ================================================================
// SECTION 9: ORDER FUNCTIONS
// ================================================================

/**
 * Generate a unique order number
 * 
 * @return string Order number (e.g., "ORD-20240115-ABC123")
 */
function generateOrderNumber() {
    return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
}

/**
 * Get CSS class for order status badge
 * 
 * @param string $status Order status
 * @return string CSS class name
 */
function getOrderStatusBadge($status) {
    $colors = [
        'pending' => 'warning',
        'processing' => 'info',
        'shipped' => 'primary',
        'delivered' => 'success',
        'cancelled' => 'danger'
    ];
    return $colors[$status] ?? 'secondary';
}

// ================================================================
// SECTION 10: USER FUNCTIONS
// ================================================================

/**
 * Get user data by ID
 * 
 * @param int $id User ID
 * @return array|null User data or null if not found
 */
function getUserById($id) {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Get all orders for a customer
 * 
 * @param int $user_id User ID
 * @return array Array of orders
 */
function getCustomerOrders($user_id) {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("
            SELECT * FROM orders 
            WHERE user_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}
?>