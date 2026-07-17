<?php
/**
 * ================================================================
 * HEADER FILE - E-COMMERCE STORE
 * 
 * This file contains the HTML head and navigation bar.
 * It is included at the top of every page.
 * ================================================================
 */

// Get the active template and cart count
$active_template = getActiveTemplate();
$template_css = SITE_URL . 'assets/css/' . $active_template . '.css';
$cart_count = getCartCount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ============================================================
    META TAGS - SEO OPTIMIZATION
    ============================================================ -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="EcomStore - Your one-stop shop for electronics, books, clothing, and more. Best prices, fast shipping.">
    <meta name="keywords" content="online store, ecommerce, shop, buy products, electronics, books, clothing, sports, toys">
    <meta name="author" content="EcomStore">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph Tags for social media sharing -->
    <meta property="og:title" content="<?php echo SITE_NAME; ?> - Shop the Best Products Online">
    <meta property="og:description" content="Discover thousands of products at unbeatable prices. Fast delivery and secure checkout.">
    <meta property="og:image" content="<?php echo SITE_URL; ?>assets/images/logo.png">
    <meta property="og:url" content="<?php echo SITE_URL; ?>">
    <meta property="og:type" content="website">
    
    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo SITE_NAME; ?> - Shop the Best Products Online">
    <meta name="twitter:description" content="Discover thousands of products at unbeatable prices.">
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo SITE_URL; ?>assets/images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo SITE_URL; ?>assets/images/favicon.ico" type="image/x-icon">
    
    <!-- Page Title -->
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    
    <!-- ============================================================
    CSS STYLESHEETS
    ============================================================ -->
    <!-- Main stylesheet -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/style.css">
    <!-- Active template stylesheet -->
    <link rel="stylesheet" href="<?php echo $template_css; ?>">
    <!-- Font Awesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Leaflet CSS for interactive maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- ============================================================
    JAVASCRIPT
    ============================================================ -->
    <!-- jQuery library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Main JavaScript file -->
    <script src="<?php echo SITE_URL; ?>assets/js/main.js" defer></script>
    <!-- Chart.js for data visualization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
    <!-- Leaflet JavaScript for maps -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- CSRF Token for form security -->
    <meta name="csrf-token" content="<?php echo generateCSRFToken(); ?>">
</head>
<body>
    <!-- ============================================================
    HEADER / NAVIGATION
    ============================================================ -->
    <header class="main-header">
        <nav class="navbar" role="navigation" aria-label="Main Navigation">
            <div class="container">
                <!-- Site Logo -->
                <div class="nav-brand">
                    <a href="<?php echo SITE_URL; ?>" aria-label="Homepage">
                        <img src="<?php echo SITE_URL; ?>assets/images/logo.png" 
                             alt="<?php echo SITE_NAME; ?> Logo" 
                             height="40">
                    </a>
                </div>
                
                <!-- Mobile Menu Toggle Button -->
                <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false">
                    <span class="hamburger"></span>
                </button>
                
                <!-- Navigation Menu -->
                <ul class="nav-menu" role="menubar">
                    <!-- Public Navigation Links -->
                    <li role="none">
                        <a href="<?php echo SITE_URL; ?>" role="menuitem">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li role="none">
                        <a href="<?php echo SITE_URL; ?>public/products.php" role="menuitem">
                            <i class="fas fa-shopping-bag"></i> Products
                        </a>
                    </li>
                    <li role="none">
                        <a href="<?php echo SITE_URL; ?>public/about.php" role="menuitem">
                            <i class="fas fa-info-circle"></i> About
                        </a>
                    </li>
                    <li role="none">
                        <a href="<?php echo SITE_URL; ?>public/contact.php" role="menuitem">
                            <i class="fas fa-envelope"></i> Contact
                        </a>
                    </li>
                    <li role="none">
                        <a href="<?php echo SITE_URL; ?>public/help/" role="menuitem">
                            <i class="fas fa-question-circle"></i> Help
                        </a>
                    </li>
                    
                    <?php if (isLoggedIn()): ?>
                        <!-- Authenticated User Links -->
                        <li role="none">
                            <a href="<?php echo SITE_URL; ?>public/dashboard.php" role="menuitem">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li role="none">
                            <a href="<?php echo SITE_URL; ?>public/profile.php" role="menuitem">
                                <i class="fas fa-user"></i> Profile
                            </a>
                        </li>
                        <?php if (isAdmin()): ?>
                            <!-- Admin Links -->
                            <li role="none">
                                <a href="<?php echo SITE_URL; ?>admin/" role="menuitem">
                                    <i class="fas fa-cog"></i> Admin
                                </a>
                            </li>
                        <?php endif; ?>
                        <li role="none">
                            <a href="<?php echo SITE_URL; ?>public/logout.php" role="menuitem">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <!-- Guest Links -->
                        <li role="none">
                            <a href="<?php echo SITE_URL; ?>public/login.php" role="menuitem">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                        <li role="none">
                            <a href="<?php echo SITE_URL; ?>public/register.php" role="menuitem" class="btn-primary">
                                <i class="fas fa-user-plus"></i> Sign Up
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <!-- Shopping Cart Link -->
                    <li role="none">
                        <a href="<?php echo SITE_URL; ?>public/cart.php" role="menuitem" class="cart-link">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-badge"><?php echo $cart_count; ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <main>