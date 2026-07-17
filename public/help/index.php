<?php
/**
 * ================================================================
 * HELP CENTER - E-COMMERCE STORE
 * 
 * Main help page with navigation to all help articles.
 * Provides users with documentation and guides.
 * ================================================================
 */

$page_title = 'Help Center';
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
require_once '../../includes/header.php';
?>

<div class="container">
    <div class="help-page">
        <h1>Help Center</h1>
        <p class="help-subtitle">Welcome to the <?php echo SITE_NAME; ?> Help Center. Find answers to your questions below.</p>
        
        <!-- ============================================================
        HELP CARDS
        ============================================================ -->
        <div class="help-grid">
            <div class="card help-card">
                <i class="fas fa-rocket fa-2x" style="color: var(--primary-color);"></i>
                <h3>Getting Started</h3>
                <p>New to <?php echo SITE_NAME; ?>? Learn how to create an account and start shopping.</p>
                <a href="getting-started.php" class="btn-secondary">Learn More</a>
            </div>
            
            <div class="card help-card">
                <i class="fas fa-shopping-bag fa-2x" style="color: var(--primary-color);"></i>
                <h3>Shopping Guide</h3>
                <p>Learn how to browse products, add to cart, and complete your purchase.</p>
                <a href="shopping-guide.php" class="btn-secondary">Learn More</a>
            </div>
            
            <div class="card help-card">
                <i class="fas fa-user-cog fa-2x" style="color: var(--primary-color);"></i>
                <h3>Account Management</h3>
                <p>Manage your profile, orders, and account preferences.</p>
                <a href="account-management.php" class="btn-secondary">Learn More</a>
            </div>
            
            <div class="card help-card">
                <i class="fas fa-tools fa-2x" style="color: var(--primary-color);"></i>
                <h3>Troubleshooting</h3>
                <p>Having issues? Find solutions to common problems here.</p>
                <a href="troubleshooting.php" class="btn-secondary">Learn More</a>
            </div>
        </div>
        
        <!-- ============================================================
        CONTACT SECTION
        ============================================================ -->
        <div class="help-contact">
            <h2>Still Need Help?</h2>
            <p>Contact our support team for personalized assistance.</p>
            <a href="../contact.php" class="btn-primary">
                <i class="fas fa-envelope"></i> Contact Support
            </a>
        </div>
    </div>
</div>

<style>
.help-page h1 {
    text-align: center;
    margin-top: 30px;
}
.help-subtitle {
    text-align: center;
    color: var(--gray-text);
    margin-bottom: 40px;
    font-size: 1.1rem;
}
.help-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
    margin-bottom: 40px;
}
.help-card {
    text-align: center;
    padding: 30px;
}
.help-card i {
    margin-bottom: 15px;
}
.help-card h3 {
    margin-bottom: 10px;
}
.help-card p {
    color: var(--gray-text);
    margin-bottom: 15px;
}
.help-contact {
    text-align: center;
    background: white;
    padding: 40px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
}
.help-contact h2 {
    margin-bottom: 10px;
}
.help-contact p {
    color: var(--gray-text);
    margin-bottom: 20px;
}
@media (max-width: 768px) {
    .help-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once '../../includes/footer.php'; ?>