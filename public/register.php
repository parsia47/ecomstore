<?php
/**
 * ================================================================
 * REGISTRATION PAGE - E-COMMERCE STORE
 * 
 * Allows users to create new customer accounts.
 * ================================================================
 */

$page_title = 'Register';
require_once '../includes/config.php';
require_once '../includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $full_name = sanitize($_POST['full_name'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $shipping_address = sanitize($_POST['shipping_address'] ?? '');
    
    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        $error = 'All fields are required.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } elseif (!preg_match('/[0-9]/', $password)) {
        $error = 'Password must contain at least one number.';
    } else {
        try {
            $pdo = getDBConnection();
            
            // Check if username or email exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                $error = 'Username or email already exists.';
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("
                    INSERT INTO users (username, email, password_hash, full_name, phone, shipping_address) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                if ($stmt->execute([$username, $email, $password_hash, $full_name, $phone, $shipping_address])) {
                    $user_id = $pdo->lastInsertId();
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['user_type'] = 'customer';
                    $_SESSION['username'] = $username;
                    $_SESSION['full_name'] = $full_name;
                    header('Location: dashboard.php');
                    exit;
                } else {
                    $error = 'Registration failed. Please try again.';
                }
            }
        } catch (Exception $e) {
            $error = 'An error occurred. Please try again later.';
            error_log("Registration error: " . $e->getMessage());
        }
    }
}

require_once '../includes/header.php';
?>

<div class="container">
    <div class="auth-container" style="max-width: 500px; margin: 40px auto;">
        <h1>Create Account</h1>
        <p>Join <?php echo SITE_NAME; ?> and start shopping today!</p>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" class="auth-form">
            <div class="form-group">
                <label for="full_name">Full Name *</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>
            
            <div class="form-group">
                <label for="username">Username *</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone">
            </div>
            
            <div class="form-group">
                <label for="shipping_address">Shipping Address</label>
                <textarea id="shipping_address" name="shipping_address" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label for="password">Password * (min 8 characters)</label>
                <input type="password" id="password" name="password" required minlength="8">
            </div>
            
            <div class="form-group">
                <label for="confirm-password">Confirm Password *</label>
                <input type="password" id="confirm-password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn-primary" style="width: 100%;">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>
        
        <p style="text-align: center; margin-top: 20px;">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </div>
</div>

<style>
.auth-container h1 {
    text-align: center;
    margin-bottom: 5px;
}
.auth-container > p {
    text-align: center;
    color: var(--gray-text);
    margin-bottom: 30px;
}
.auth-form {
    background: white;
    padding: 30px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
}
</style>

<?php require_once '../includes/footer.php'; ?>