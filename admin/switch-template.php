<?php
/**
 * ================================================================
 * ADMIN - SWITCH TEMPLATE (AJAX)
 * 
 * AJAX endpoint for switching templates.
 * ================================================================
 */

require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check admin access
if (!isLoggedIn() || !isAdmin()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get template from request
$input = json_decode(file_get_contents('php://input'), true);
$template = $input['template'] ?? '';

// Validate template
$allowed_templates = ['template1', 'template2', 'template3'];
if (!in_array($template, $allowed_templates)) {
    echo json_encode(['success' => false, 'message' => 'Invalid template']);
    exit;
}

try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = 'active_template'");
    
    if ($stmt->execute([$template])) {
        echo json_encode(['success' => true, 'message' => 'Template updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update template']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
exit;
?>