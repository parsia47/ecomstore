<?php
/**
 * ================================================================
 * LOGOUT - E-COMMERCE STORE
 * 
 * Destroys user session and redirects to home.
 * ================================================================
 */

// Start session and destroy all session data
session_start();
session_destroy();

// Redirect to home page
header('Location: index.php');
exit;
?>