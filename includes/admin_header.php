<?php
/**
 * ================================================================
 * ADMIN HEADER FILE - E-COMMERCE STORE
 * 
 * This file contains the admin panel header and navigation.
 * It is included at the top of every admin page.
 * ================================================================
 */

// Get the active template
$active_template = getActiveTemplate();
$template_css = SITE_URL . 'assets/css/' . $active_template . '.css';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Prevent search engines from indexing admin pages -->
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Admin Panel - <?php echo SITE_NAME; ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo $template_css; ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="<?php echo SITE_URL; ?>assets/js/main.js" defer></script>
</head>
<body>
    <!-- ============================================================
    ADMIN HEADER / NAVIGATION
    ============================================================ -->
    <header class="admin-header">
        <nav class="navbar admin-nav" role="navigation" aria-label="Admin Navigation">
            <div class="container">
                <!-- Admin Brand -->
                <div class="nav-brand">
                    <a href="<?php echo SITE_URL; ?>admin/">
                        <i class="fas fa-cog"></i> Admin Panel
                    </a>
                </div>
                
                <!-- Admin Navigation Menu -->
                <ul class="nav-menu" role="menubar">
                    <li role="none">
                        <a href="<?php echo SITE_URL; ?>admin/index.php" role="menuitem">
                            <i class="fas fa-dashboard"></i> Dashboard
                        </a>
                    </li>
                    <li role="none">
                        <a href="<?php echo SITE_URL; ?>admin/products.php" role="menuitem">
                            <i class="fas fa-box"></i> Products
                        </a>
                    </li>
                    <li role="none">
                        <a href="<?php echo SITE_URL; ?>admin/orders.php" role="menuitem">
                            <i class="fas fa-shopping-cart"></i> Orders
                        </a>
                    </li>
                    <li role="none">
                        <a href="<?php echo SITE_URL; ?>admin/templates.php" role="menuitem">
                            <i class="fas fa-palette"></i> Templates
                        </a>
                    </li>
                    <li role="none">
                        <a href="<?php echo SITE_URL; ?>admin/users.php" role="menuitem">
                            <i class="fas fa-users"></i> Users
                        </a>
                    </li>
                    <li role="none">
                        <a href="<?php echo SITE_URL; ?>admin/monitoring.php" role="menuitem">
                            <i class="fas fa-heartbeat"></i> Monitoring
                        </a>
                    </li>
                    <li role="none">
                        <a href="<?php echo SITE_URL; ?>" role="menuitem">
                            <i class="fas fa-home"></i> Site
                        </a>
                    </li>
                    <li role="none">
                        <a href="<?php echo SITE_URL; ?>public/logout.php" role="menuitem">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <main>