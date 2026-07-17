<?php
/**
 * ================================================================
 * TROUBLESHOOTING HELP - E-COMMERCE STORE
 * 
 * Common issues and solutions for users.
 * ================================================================
 */

$page_title = 'Troubleshooting';
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
require_once '../../includes/header.php';
?>

<div class="container">
    <div class="help-article">
        <h1>Troubleshooting</h1>
        
        <div class="article-content">
            <!-- ============================================================
            COMMON ISSUES
            ============================================================ -->
            <section>
                <h2>Common Issues and Solutions</h2>
                
                <div class="troubleshoot-item">
                    <h3>I Can't Log In</h3>
                    <ul>
                        <li>Make sure you're using the correct username/email and password</li>
                        <li>Check if your account is still active (contact support if disabled)</li>
                        <li>Try resetting your password</li>
                        <li>Clear your browser cache and cookies</li>
                    </ul>
                </div>
                
                <div class="troubleshoot-item">
                    <h3>My Cart Shows Empty</h3>
                    <ul>
                        <li>Make sure you're logged in (cart is tied to your account)</li>
                        <li>Check if you were logged out</li>
                        <li>Try adding items again</li>
                        <li>Contact support if issue persists</li>
                    </ul>
                </div>
                
                <div class="troubleshoot-item">
                    <h3>I Can't Checkout</h3>
                    <ul>
                        <li>Ensure your cart has items</li>
                        <li>Fill in all required fields (shipping address, payment method)</li>
                        <li>Check if your cart total meets minimum order requirements</li>
                        <li>Try using a different payment method</li>
                    </ul>
                </div>
                
                <div class="troubleshoot-item">
                    <h3>My Order Wasn't Confirmed</h3>
                    <ul>
                        <li>Check your email for confirmation</li>
                        <li>Check your order history in dashboard</li>
                        <li>Wait a few minutes for processing</li>
                        <li>Contact support if you don't receive confirmation</li>
                    </ul>
                </div>
                
                <div class="troubleshoot-item">
                    <h3>Technical Issues</h3>
                    <ul>
                        <li>Use a supported browser (Chrome, Firefox, Safari, Edge)</li>
                        <li>Check your internet connection</li>
                        <li>Disable ad-blockers that might interfere</li>
                        <li>Clear your browser cache</li>
                        <li>Try using a different device</li>
                    </ul>
                </div>
            </section>
            
            <!-- ============================================================
            CONTACT SUPPORT
            ============================================================ -->
            <section>
                <h2>Still Having Problems?</h2>
                <p>If you've tried everything and still need help:</p>
                <ol>
                    <li>Take screenshots of the issue</li>
                    <li>Note the time and steps that led to the problem</li>
                    <li><a href="../contact.php">Contact our support team</a> with the details</li>
                </ol>
                
                <div class="video-container">
                    <video controls style="width: 100%; max-width: 600px; border-radius: 8px;">
                        <source src="<?php echo SITE_URL; ?>assets/videos/troubleshooting.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </section>
        </div>
        
        <!-- ============================================================
        NAVIGATION
        ============================================================ -->
        <div class="article-navigation">
            <a href="account-management.php" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Previous: Account Management
            </a>
            <a href="index.php" class="btn-primary">
                Back to Help Center <i class="fas fa-arrow-right"></i>
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
.article-content h3 {
    color: var(--secondary-color);
    margin: 15px 0 10px;
}
.troubleshoot-item {
    padding: 15px;
    margin-bottom: 15px;
    background: var(--light-bg);
    border-radius: var(--border-radius);
    border-left: 4px solid var(--primary-color);
}
.troubleshoot-item h3 {
    margin-top: 0;
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