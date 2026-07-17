<?php
/**
 * ================================================================
 * ADMIN - SYSTEM MONITORING - E-COMMERCE STORE
 * 
 * Displays system status and performance metrics.
 * ================================================================
 */

$page_title = 'System Monitoring';
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check admin access
if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../public/login.php');
    exit;
}

$pdo = getDBConnection();

// ============================================================
// SERVICE CHECKS
// ============================================================
$services = [
    'database' => [
        'name' => 'MySQL Database',
        'status' => 'online',
        'check' => function() use ($pdo) {
            try {
                $pdo->query("SELECT 1");
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
    ],
    'php' => [
        'name' => 'PHP Engine',
        'status' => 'online',
        'check' => function() {
            return function_exists('version_compare');
        }
    ],
    'session' => [
        'name' => 'Session System',
        'status' => 'online',
        'check' => function() {
            return isset($_SESSION) || session_status() === PHP_SESSION_ACTIVE;
        }
    ],
    'mail' => [
        'name' => 'Mail Service',
        'status' => 'unknown',
        'check' => function() {
            return function_exists('mail');
        }
    ],
    'uploads' => [
        'name' => 'File Uploads',
        'status' => 'unknown',
        'check' => function() {
            return is_writable('../assets/images/products/');
        }
    ],
    'server' => [
        'name' => 'Web Server',
        'status' => 'online',
        'check' => function() {
            return isset($_SERVER['SERVER_SOFTWARE']);
        }
    ]
];

// Run checks
foreach ($services as &$service) {
    try {
        $result = $service['check']();
        $service['status'] = $result ? 'online' : 'offline';
    } catch (Exception $e) {
        $service['status'] = 'error';
        $service['error'] = $e->getMessage();
    }
}

// ============================================================
// SYSTEM INFORMATION
// ============================================================
try {
    $system_info = [
        'php_version' => phpversion(),
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
        'mysql_version' => $pdo->getAttribute(PDO::ATTR_SERVER_VERSION),
        'memory_limit' => ini_get('memory_limit'),
        'max_execution_time' => ini_get('max_execution_time'),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
    ];
} catch (Exception $e) {
    $system_info = [];
    error_log("System info error: " . $e->getMessage());
}

// ============================================================
// DATABASE STATISTICS
// ============================================================
try {
    $tables = $pdo->query("SHOW TABLE STATUS")->fetchAll();
} catch (Exception $e) {
    $tables = [];
}

require_once '../includes/admin_header.php';
?>

<div class="admin-container">
    <h1>System Monitoring</h1>
    
    <!-- ============================================================
    SERVICE STATUS
    ============================================================ -->
    <div class="monitoring-section">
        <h2>Service Status</h2>
        <div class="services-list">
            <?php foreach ($services as $key => $service): ?>
                <div class="service-item <?php echo $service['status']; ?>">
                    <div class="service-name">
                        <span class="status-dot"></span>
                        <?php echo $service['name']; ?>
                    </div>
                    <div class="service-status">
                        <span class="badge badge-<?php echo $service['status']; ?>">
                            <?php echo ucfirst($service['status']); ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- ============================================================
    SYSTEM INFORMATION
    ============================================================ -->
    <div class="monitoring-section">
        <h2>System Information</h2>
        <div class="table-responsive">
            <table>
                <?php foreach ($system_info as $key => $value): ?>
                    <tr>
                        <th><?php echo str_replace('_', ' ', ucwords($key)); ?></th>
                        <td><?php echo $value; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    
    <!-- ============================================================
    DATABASE STATISTICS
    ============================================================ -->
    <div class="monitoring-section">
        <h2>Database Statistics</h2>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Table Name</th>
                        <th>Rows</th>
                        <th>Size</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tables as $table): ?>
                        <tr>
                            <td><?php echo $table['Name']; ?></td>
                            <td><?php echo number_format($table['Rows'] ?? 0); ?></td>
                            <td><?php echo number_format(($table['Data_length'] + $table['Index_length']) / 1024, 2); ?> KB</td>
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
.monitoring-section {
    background: white;
    padding: 20px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    margin-bottom: 30px;
}
.monitoring-section h2 {
    margin-bottom: 15px;
}
.services-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 10px;
}
.service-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 15px;
    border-radius: var(--border-radius);
    background: var(--light-bg);
    border-left: 4px solid var(--gray-text);
}
.service-item.online {
    border-left-color: var(--success-color);
}
.service-item.offline {
    border-left-color: var(--danger-color);
}
.service-item.error {
    border-left-color: var(--warning-color);
}
.service-item .status-dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 8px;
}
.service-item.online .status-dot {
    background: var(--success-color);
}
.service-item.offline .status-dot {
    background: var(--danger-color);
}
.service-item.error .status-dot {
    background: var(--warning-color);
}
.badge-online {
    background: var(--success-color);
    color: white;
}
.badge-offline {
    background: var(--danger-color);
    color: white;
}
.badge-error {
    background: var(--warning-color);
    color: white;
}
.badge-unknown {
    background: var(--gray-text);
    color: white;
}
</style>

<?php require_once '../includes/footer.php'; ?>