<?php
/**
 * ================================================================
 * WIKI MAIN PAGE - E-COMMERCE STORE
 * 
 * Documentation hub for developers and administrators.
 * ================================================================
 */

$page_title = 'Wiki';
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
require_once '../../includes/header.php';
?>

<div class="container">
    <div class="wiki-page">
        <h1><?php echo SITE_NAME; ?> Wiki</h1>
        <p class="wiki-subtitle">Complete documentation for using and managing the platform.</p>
        
        <!-- ============================================================
        WIKI CARDS
        ============================================================ -->
        <div class="wiki-grid">
            <div class="card wiki-card">
                <i class="fas fa-server fa-2x" style="color: var(--primary-color);"></i>
                <h3>Setup &amp; Installation</h3>
                <p>Learn how to set up <?php echo SITE_NAME; ?> on your own server.</p>
                <a href="setup.php" class="btn-secondary">Read More</a>
            </div>
            
            <div class="card wiki-card">
                <i class="fas fa-edit fa-2x" style="color: var(--primary-color);"></i>
                <h3>Content Management</h3>
                <p>How to manage products, categories, and orders.</p>
                <a href="content.php" class="btn-secondary">Read More</a>
            </div>
            
            <div class="card wiki-card">
                <i class="fas fa-palette fa-2x" style="color: var(--primary-color);"></i>
                <h3>Template System</h3>
                <p>Customize the look and feel of your site.</p>
                <a href="templates.php" class="btn-secondary">Read More</a>
            </div>
            
            <div class="card wiki-card">
                <i class="fas fa-code fa-2x" style="color: var(--primary-color);"></i>
                <h3>Customization</h3>
                <p>Advanced customization and development guide.</p>
                <a href="customization.php" class="btn-secondary">Read More</a>
            </div>
        </div>
    </div>
</div>

<style>
.wiki-page h1 {
    text-align: center;
    margin-top: 30px;
}
.wiki-subtitle {
    text-align: center;
    color: var(--gray-text);
    margin-bottom: 40px;
    font-size: 1.1rem;
}
.wiki-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
    margin-bottom: 40px;
}
.wiki-card {
    text-align: center;
    padding: 30px;
}
.wiki-card i {
    margin-bottom: 15px;
}
.wiki-card h3 {
    margin-bottom: 10px;
}
.wiki-card p {
    color: var(--gray-text);
    margin-bottom: 15px;
}
@media (max-width: 768px) {
    .wiki-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once '../../includes/footer.php'; ?>