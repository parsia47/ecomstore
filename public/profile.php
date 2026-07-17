<?php
/**
 * ================================================================
 * PROFILE PAGE - E-COMMERCE STORE
 * 
 * Allows users to view and edit their profile information.
 * ================================================================
 */

$page_title = 'My Profile';
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = getUserId();
$message = '';
$error = '';

try {
    $pdo = getDBConnection();
    $user = getUserById($user_id);
    
    // Handle profile update
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
        $full_name = sanitize($_POST['full_name'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $shipping_address = sanitize($_POST['shipping_address'] ?? '');
        
        if (empty($full_name)) {
            $error = 'Full name is required.';
        } else {
            $stmt = $pdo->prepare("
                UPDATE users 
                SET full_name = ?, phone = ?, shipping_address = ? 
                WHERE id = ?
            ");
            if ($stmt->execute([$full_name, $phone, $shipping_address, $user_id])) {
                $message = 'Profile updated successfully!';
                $user = getUserById($user_id);
            } else {
                $error = 'Failed to update profile.';
            }
        }
    }
    
    // Handle password change
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_new_password = $_POST['confirm_new_password'] ?? '';
        
        if (empty($current_password) || empty($new_password) || empty($confirm_new_password)) {
            $error = 'All password fields are required.';
        } elseif ($new_password !== $confirm_new_password) {
            $error = 'New passwords do not match.';
        } elseif (strlen($new_password) < 8) {
            $error = 'New password must be at least 8 characters.';
        } else {
            $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user_data = $stmt->fetch();
            
            if (password_verify($current_password, $user_data['password_hash'])) {
                $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
                if ($stmt->execute([$new_hash, $user_id])) {
                    $message = 'Password changed successfully!';
                } else {
                    $error = 'Failed to change password.';
                }
            } else {
                $error = 'Current password is incorrect.';
            }
        }
    }
    
} catch (Exception $e) {
    $error = 'An error occurred. Please try again.';
    error_log("Profile error: " . $e->getMessage());
}

require_once '../includes/header.php';
?>

<div class="container">
    <div class="profile-container">
        <h1>My Profile</h1>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <!-- ============================================================
        PROFILE INFORMATION
        ============================================================ -->
        <div class="profile-card">
            <h2>Profile Information</h2>
            <form method="POST" action="">
                <input type="hidden" name="update_profile" value="1">
                
                <div class="form-group">
                    <label for="full_name">Full Name *</label>
                    <input type="text" id="full_name" name="full_name" 
                           value="<?php echo $user['full_name']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email (Cannot be changed)</label>
                    <input type="email" id="email" value="<?php echo $user['email']; ?>" disabled>
                </div>
                
                <div class="form-group">
                    <label for="username">Username (Cannot be changed)</label>
                    <input type="text" id="username" value="<?php echo $user['username']; ?>" disabled>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" 
                           value="<?php echo $user['phone']; ?>">
                </div>
                
                <div class="form-group">
                    <label for="shipping_address">Shipping Address</label>
                    <textarea id="shipping_address" name="shipping_address" rows="4">
                        <?php echo $user['shipping_address']; ?>
                    </textarea>
                </div>
                
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Update Profile
                </button>
            </form>
        </div>
        
        <!-- ============================================================
        CHANGE PASSWORD
        ============================================================ -->
        <div class="profile-card">
            <h2>Change Password</h2>
            <form method="POST" action="">
                <input type="hidden" name="change_password" value="1">
                
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                
                <div class="form-group">
                    <label for="new_password">New Password (min 8 characters)</label>
                    <input type="password" id="new_password" name="new_password" required minlength="8">
                </div>
                
                <div class="form-group">
                    <label for="confirm_new_password">Confirm New Password</label>
                    <input type="password" id="confirm_new_password" name="confirm_new_password" required>
                </div>
                
                <button type="submit" class="btn-secondary">
                    <i class="fas fa-key"></i> Change Password
                </button>
            </form>
        </div>
    </div>
</div>

<style>
.profile-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 30px 0;
}
.profile-container h1 {
    margin-bottom: 30px;
}
.profile-card {
    background: white;
    padding: 30px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    margin-bottom: 30px;
}
.profile-card h2 {
    margin-bottom: 20px;
}
.profile-card input[disabled] {
    background: var(--light-bg);
    cursor: not-allowed;
}
</style>

<?php require_once '../includes/footer.php'; ?>