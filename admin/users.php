<?php
/**
 * ================================================================
 * ADMIN - USER MANAGEMENT - E-COMMERCE STORE
 * 
 * Manage all user accounts.
 * ================================================================
 */

$page_title = 'User Management';
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check admin access
if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../public/login.php');
    exit;
}

$pdo = getDBConnection();
$message = '';

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['user_id'])) {
        $user_id = (int)$_POST['user_id'];
        $action = $_POST['action'];
        
        try {
            if ($action === 'disable') {
                $stmt = $pdo->prepare("UPDATE users SET is_active = 0 WHERE id = ? AND user_type != 'admin'");
                if ($stmt->execute([$user_id])) {
                    $message = 'User disabled successfully.';
                }
            } elseif ($action === 'enable') {
                $stmt = $pdo->prepare("UPDATE users SET is_active = 1 WHERE id = ?");
                if ($stmt->execute([$user_id])) {
                    $message = 'User enabled successfully.';
                }
            } elseif ($action === 'delete') {
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND user_type != 'admin'");
                if ($stmt->execute([$user_id])) {
                    $message = 'User deleted successfully.';
                }
            }
        } catch (Exception $e) {
            $message = 'Action failed.';
            error_log("User management error: " . $e->getMessage());
        }
    }
}

// Get all users
$users = $pdo->query("
    SELECT u.*, 
           (SELECT COUNT(*) FROM orders WHERE user_id = u.id) as order_count,
           (SELECT SUM(total_amount) FROM orders WHERE user_id = u.id AND order_status = 'delivered') as total_spent
    FROM users u 
    ORDER BY u.created_at DESC
")->fetchAll();

require_once '../includes/admin_header.php';
?>

<div class="admin-container">
    <h1>User Management</h1>
    
    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <!-- ============================================================
    USERS TABLE
    ============================================================ -->
    <div class="users-list">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Orders</th>
                        <th>Spent</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['full_name']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td>
                                <span class="badge badge-<?php echo $user['user_type']; ?>">
                                    <?php echo ucfirst($user['user_type']); ?>
                                </span>
                            </td>
                            <td><?php echo $user['order_count'] ?? 0; ?></td>
                            <td><?php echo formatPrice($user['total_spent'] ?? 0); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $user['is_active'] ? 'success' : 'danger'; ?>">
                                    <?php echo $user['is_active'] ? 'Active' : 'Disabled'; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($user['user_type'] !== 'admin'): ?>
                                    <form method="POST" action="" style="display: inline;">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <?php if ($user['is_active']): ?>
                                            <input type="hidden" name="action" value="disable">
                                            <button type="submit" class="btn-danger btn-sm">Disable</button>
                                        <?php else: ?>
                                            <input type="hidden" name="action" value="enable">
                                            <button type="submit" class="btn-success btn-sm">Enable</button>
                                        <?php endif; ?>
                                    </form>
                                    <form method="POST" action="" style="display: inline;">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="btn-danger btn-sm" 
                                                onclick="return confirm('Delete this user?')">Delete</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted">Protected</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.admin-container h1 {
    margin: 30px 0;
}
.users-list {
    background: white;
    padding: 20px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
}
.btn-sm {
    padding: 5px 10px;
    font-size: 0.8rem;
}
.text-muted {
    color: var(--gray-text);
}
.badge-admin {
    background: var(--danger-color);
    color: white;
}
.badge-customer {
    background: var(--primary-color);
    color: white;
}
.badge-success {
    background: var(--success-color);
    color: white;
}
.badge-danger {
    background: var(--danger-color);
    color: white;
}
</style>

<?php require_once '../includes/footer.php'; ?>