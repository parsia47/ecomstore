<?php
/**
 * ================================================================
 * ACCOUNT MANAGEMENT HELP - E-COMMERCE STORE
 * 
 * Guide for managing user accounts and profiles.
 * ================================================================
 */

$page_title = 'Account Management';
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
require_once '../../includes/header.php';
?>

<div class="container">
    <div class="help-article">
        <h1>Account Management</h1>
        
        <div class="article-content">
            <!-- ============================================================
            PROFILE MANAGEMENT
            ============================================================ -->
            <section>
                <h2>Managing Your Profile</h2>
                
                <h3>Update Your Information</h3>
                <ul>
                    <li>Go to your <a href="../profile.php">Profile Page</a></li>
                    <li>Update your:
                        <ul>
                            <li>Full Name</li>
                            <li>Phone Number</li>
                            <li>Shipping Address</li>
                        </ul>
                    </li>
                    <li>Click "Update Profile" to save changes</li>
                </ul>
                
                <h3>Change Your Password</h3>
                <ol>
                    <li>Go to your profile page</li>
                    <li>Find the "Change Password" section</li>
                    <li>Enter your current password</li>
                    <li>Enter your new password (minimum 8 characters)</li>
                    <li>Confirm your new password</li>
                    <li>Click "Change Password"</li>
                </ol>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    Your email and username cannot be changed. Contact support if you need to update them.
                </div>
            </section>
            
            <!-- ============================================================
            ORDER HISTORY
            ============================================================ -->
            <section>
                <h2>Viewing Order History</h2>
                <ul>
                    <li>Go to your <a href="../dashboard.php">Dashboard</a></li>
                    <li>Click "View All Orders" or go to <a href="../order-history.php">Order History</a></li>
                    <li>See all your past orders with details</li>
                    <li>Filter orders by status</li>
                    <li>Click "View" on any order for more details</li>
                </ul>
            </section>
            
            <!-- ============================================================
            ACCOUNT SECURITY
            ============================================================ -->
            <section>
                <h2>Account Security</h2>
                <div class="alert alert-success">
                    <i class="fas fa-shield-alt"></i> Tips for keeping your account secure:
                </div>
                <ul>
                    <li>Use a strong, unique password</li>
                    <li>Never share your password with anyone</li>
                    <li>Log out when using a public computer</li>
                    <li>Contact support if you notice suspicious activity</li>
                </ul>
            </section>
        </div>
        
        <!-- ============================================================
        NAVIGATION
        ============================================================ -->
        <div class="article-navigation">
            <a href="shopping-guide.php" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Previous: Shopping Guide
            </a>
            <a href="troubleshooting.php" class="btn-primary">
                Next: Troubleshooting <i class="fas fa-arrow-right"></i>
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
    margin: 20px 0 10px;
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