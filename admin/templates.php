<?php
/**
 * ================================================================
 * ADMIN - TEMPLATE MANAGEMENT - E-COMMERCE STORE
 * 
 * Manage and switch site templates.
 * ================================================================
 */

$page_title = 'Template Management';
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check admin access
if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../public/login.php');
    exit;
}

$pdo = getDBConnection();
$current_template = getActiveTemplate();
$message = '';

// Handle template switching
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['template'])) {
    $template = sanitize($_POST['template']);
    $allowed_templates = ['template1', 'template2', 'template3'];
    
    if (in_array($template, $allowed_templates)) {
        try {
            $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = 'active_template'");
            if ($stmt->execute([$template])) {
                $message = 'Template updated successfully!';
                $current_template = $template;
            }
        } catch (Exception $e) {
            $message = 'Failed to update template.';
            error_log("Template error: " . $e->getMessage());
        }
    }
}

// Template data
$templates = [
    'template1' => [
        'name' => 'Modern Orange',
        'description' => 'Warm, energetic orange theme with gradient accents.',
        'preview' => '../assets/images/templates/blue-preview.jpg'
    ],
    'template2' => [
        'name' => 'Cool Blue',
        'description' => 'Professional, trustworthy blue theme.',
        'preview' => '../assets/images/templates/autumn-preview.jpg'
    ],
    'template3' => [
        'name' => 'Nature Green',
        'description' => 'Fresh, eco-friendly green theme.',
        'preview' => '../assets/images/templates/green-preview.jpg'
    ]
];

require_once '../includes/admin_header.php';
?>

<div class="admin-container">
    <h1>Template Management</h1>
    
    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <!-- ============================================================
    TEMPLATE CARDS
    ============================================================ -->
    <div class="template-grid">
        <?php foreach ($templates as $key => $template): ?>
            <div class="card template-card <?php echo $key === $current_template ? 'active' : ''; ?>">
                <div class="template-preview">
                    <img src="<?php echo $template['preview']; ?>" 
                         alt="<?php echo $template['name']; ?>" 
                         onerror="this.src='<?php echo SITE_URL; ?>assets/images/default-preview.jpg'">
                    <?php if ($key === $current_template): ?>
                        <span class="active-badge">Active</span>
                    <?php endif; ?>
                </div>
                <div class="template-info">
                    <h3><?php echo $template['name']; ?></h3>
                    <p><?php echo $template['description']; ?></p>
                    <?php if ($key !== $current_template): ?>
                        <form method="POST" action="">
                            <input type="hidden" name="template" value="<?php echo $key; ?>">
                            <button type="submit" class="btn-primary">Apply Template</button>
                        </form>
                    <?php else: ?>
                        <button class="btn-secondary" disabled>Currently Active</button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- ============================================================
    CUSTOM CSS
    ============================================================ -->
    <div class="template-customization">
        <h2>Custom CSS</h2>
        <p>You can add custom CSS to override the default styles:</p>
        <form method="POST" action="">
            <div class="form-group">
                <label for="custom_css">Custom CSS</label>
                <textarea id="custom_css" name="custom_css" rows="10" 
                          style="font-family: monospace; width: 100%;">/* Add your custom CSS here */
                    
/* Example: Change primary color */
/* :root { */
/*     --primary-color: #your-color; */
/* } */</textarea>
            </div>
            <button type="submit" class="btn-primary">Save Custom CSS</button>
        </form>
    </div>
</div>

<style>
.template-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    margin-bottom: 30px;
}
.template-card {
    position: relative;
    padding: 0;
    overflow: hidden;
}
.template-card.active {
    border: 3px solid var(--success-color);
}
.template-preview {
    position: relative;
    height: 200px;
    overflow: hidden;
    background: var(--light-bg);
}
.template-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.active-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: var(--success-color);
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: bold;
}
.template-info {
    padding: 20px;
}
.template-info h3 {
    margin-bottom: 5px;
}
.template-info p {
    color: var(--gray-text);
    margin-bottom: 15px;
}
.template-customization {
    background: white;
    padding: 20px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
}
.template-customization h2 {
    margin-bottom: 10px;
}
.template-customization p {
    color: var(--gray-text);
    margin-bottom: 15px;
}
@media (max-width: 768px) {
    .template-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once '../includes/footer.php'; ?>