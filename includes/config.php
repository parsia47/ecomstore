<?php
/**
 * ================================================================
 * CONFIGURATION FILE - E-COMMERCE STORE
 * 
 * This file contains all site-wide configuration settings.
 * Update these values before deploying your site.
 * ================================================================
 */

// ----------------------------------------------------------------
// SECTION 1: DATABASE CONFIGURATION
// FIXED for XAMPP localhost
// ----------------------------------------------------------------
define('DB_HOST', 'localhost');          // Database server hostname
define('DB_USER', 'root');               // MySQL username (CHANGED to root)
define('DB_PASS', '');                   // MySQL password (CHANGED to empty for XAMPP)
define('DB_NAME', 'ecomstore');          // Database name

// ----------------------------------------------------------------
// SECTION 2: SITE CONFIGURATION
// FIXED for localhost
// ----------------------------------------------------------------
define('SITE_NAME', 'EcomStore');        // Site name (appears in title and footer) 
define('SITE_URL', 'http://localhost/ecomstore/'); // Full site URL (CHANGED for localhost) 
define('ADMIN_EMAIL', 'admin@ecomstore.com'); // Admin email for notifications
define('CURRENCY_SYMBOL', '$');          // Currency symbol for prices
define('TAX_RATE', 13.00);               // Tax rate percentage (e.g., 13 for 13%)

// ----------------------------------------------------------------
// SECTION 3: SESSION MANAGEMENT
// Start PHP session for user authentication
// ----------------------------------------------------------------
session_start();

// ----------------------------------------------------------------
// SECTION 4: ERROR REPORTING
// Disable in production for security
// ----------------------------------------------------------------
error_reporting(E_ALL);                  // Report all PHP errors
ini_set('display_errors', 1);            // Show errors (set to 0 in production)

// ----------------------------------------------------------------
// SECTION 5: TIMEZONE
// Set your local timezone
// ----------------------------------------------------------------
date_default_timezone_set('America/New_York');

// ----------------------------------------------------------------
// SECTION 6: DATABASE CONNECTION FUNCTION
// Returns a PDO database connection object
// ----------------------------------------------------------------
function getDBConnection() {
    /**
     * Establishes a PDO database connection with error handling
     * 
     * @return PDO Database connection object
     * @throws Exception If connection fails
     */
    try {
        // Create new PDO connection with MySQL
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS
        );
        
        // Set error mode to exceptions for better error handling
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Set default fetch mode to associative array
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        // Return the connection
        return $pdo;
        
    } catch (PDOException $e) {
        // Log the error for debugging
        error_log("Database Connection Error: " . $e->getMessage());
        
        // Show user-friendly error message
        die("We're experiencing technical difficulties. Please try again later.");
    }
}
?>