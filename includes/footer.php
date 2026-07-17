<?php
/**
 * ================================================================
 * FOOTER FILE - E-COMMERCE STORE
 * 
 * This file contains the site footer and closing HTML tags.
 * It is included at the bottom of every page.
 * ================================================================
 */
?>
    </main>
    
    <!-- ============================================================
    FOOTER
    ============================================================ -->
    <footer class="main-footer" role="contentinfo">
        <div class="container">
            <div class="footer-content">
                <!-- About Section -->
                <div class="footer-section">
                    <h3>About <?php echo SITE_NAME; ?></h3>
                    <p>Your one-stop shop for electronics, books, clothing, home goods, and more. Quality products at the best prices.</p>
                    <div class="footer-contact">
                        <p><i class="fas fa-envelope"></i> <?php echo ADMIN_EMAIL; ?></p>
                        <p><i class="fas fa-phone"></i> +1 (555) 123-4567</p>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="<?php echo SITE_URL; ?>public/products.php">All Products</a></li>
                        <li><a href="<?php echo SITE_URL; ?>public/about.php">About Us</a></li>
                        <li><a href="<?php echo SITE_URL; ?>public/contact.php">Contact</a></li>
                        <li><a href="<?php echo SITE_URL; ?>public/help/">Help Center</a></li>
                        <li><a href="<?php echo SITE_URL; ?>public/faq.php">FAQ</a></li>
                    </ul>
                </div>
                
                <!-- My Account -->
                <div class="footer-section">
                    <h3>My Account</h3>
                    <ul>
                        <li><a href="<?php echo SITE_URL; ?>public/register.php">Sign Up</a></li>
                        <li><a href="<?php echo SITE_URL; ?>public/login.php">Login</a></li>
                        <li><a href="<?php echo SITE_URL; ?>public/dashboard.php">My Dashboard</a></li>
                        <li><a href="<?php echo SITE_URL; ?>public/order-history.php">Order History</a></li>
                    </ul>
                </div>
                
                <!-- Social Media & Newsletter -->
                <div class="footer-section">
                    <h3>Follow Us</h3>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                    <div class="newsletter">
                        <h4>Newsletter</h4>
                        <form action="#" method="POST">
                            <input type="email" placeholder="Your email" required>
                            <button type="submit" class="btn-primary">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- ============================================================
    BACK TO TOP BUTTON
    ============================================================ -->
    <button id="back-to-top" class="btn-primary" 
            style="position: fixed; bottom: 20px; right: 20px; display: none; border-radius: 50%; width: 50px; height: 50px; padding: 0; font-size: 20px;"
            aria-label="Back to top">
        <i class="fas fa-arrow-up"></i>
    </button>
    
    <!-- ============================================================
    JAVASCRIPT - Back to Top
    ============================================================ -->
    <script>
        // Get the back-to-top button element
        const backToTopBtn = document.getElementById('back-to-top');
        
        // Show/hide button based on scroll position
        if (backToTopBtn) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 300) {
                    backToTopBtn.style.display = 'block';
                } else {
                    backToTopBtn.style.display = 'none';
                }
            });
            
            // Scroll to top when clicked
            backToTopBtn.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
    </script>
</body>
</html>