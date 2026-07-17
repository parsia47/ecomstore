<?php
/**
 * ================================================================
 * LOGIN PAGE - E-COMMERCE STORE
 * 
 * Authenticates users and starts sessions.
 * ================================================================
 */

$page_title = 'Login';
require_once '../includes/config.php';
require_once '../includes/functions.php';

$error = '';
$redirect = isset($_GET['redirect']) ? sanitize($_GET['redirect']) : 'dashboard.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password_hash'])) {
                if ($user['is_active'] == 1) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_type'] = $user['user_type'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['full_name'] = $user['full_name'];
                    
                    $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                    $stmt->execute([$user['id']]);
                    
                    header("Location: $redirect");
                    exit;
                } else {
                    $error = 'Your account has been disabled. Please contact support.';
                }
            } else {
                $error = 'Invalid username or password.';
            }
        } catch (Exception $e) {
            $error = 'An error occurred. Please try again later.';
            error_log("Login error: " . $e->getMessage());
        }
    }
}

require_once '../includes/header.php';
?>

<div class="container">
    <div class="auth-container" style="max-width: 400px; margin: 40px auto;">
        <h1>Welcome Back!</h1>
        <p>Login to your <?php echo SITE_NAME; ?> account</p>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" class="auth-form">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-primary" style="width: 100%;">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>
        
        <p style="text-align: center; margin-top: 20px;">
            Don't have an account? <a href="register.php">Register here</a>
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