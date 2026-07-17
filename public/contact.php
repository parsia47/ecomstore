<?php
/**
 * ================================================================
 * CONTACT PAGE - E-COMMERCE STORE
 * 
 * Allows users to contact customer support.
 * ================================================================
 */

$page_title = 'Contact Us';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Invalid email address.';
    } else {
        $to = ADMIN_EMAIL;
        $email_subject = "Contact Form: " . $subject;
        $email_body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
        $headers = "From: $email\r\nReply-To: $email";
        
        if (mail($to, $email_subject, $email_body, $headers)) {
            $success_message = 'Thank you for your message! We will get back to you soon.';
        } else {
            $error_message = 'Failed to send message. Please try again later.';
        }
    }
}
?>

<div class="container">
    <div class="contact-page">
        <h1>Contact Us</h1>
        
        <div class="contact-grid">
            <!-- ============================================================
            CONTACT INFO
            ============================================================ -->
            <div class="contact-info">
                <h2>Get in Touch</h2>
                <p>Have questions about your order or products? We're here to help!</p>
                
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>Email</h4>
                        <p><?php echo ADMIN_EMAIL; ?></p>
                    </div>
                </div>
                
                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h4>Phone</h4>
                        <p>+1 (555) 123-4567</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <h4>Hours</h4>
                        <p>Mon-Fri: 9am - 6pm EST</p>
                    </div>
                </div>
                
                <!-- ============================================================
                INTERACTIVE MAP
                ============================================================ -->
                <div class="map-container">
                    <h3>Find Us</h3>
                    <div id="store-map" style="height: 300px; border-radius: 8px; overflow: hidden;">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2948.603903707641!2d-83.03727868454342!3d42.31492637918721!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x883b2d1b05be449b%3A0xbc6e7bb4baa66f65!2sUniversity%20of%20Windsor!5e0!3m2!1sen!2sca!4v1700000000000" 
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy"
                            title="Store location map">
                        </iframe>
                    </div>
                </div>
            </div>
            
            <!-- ============================================================
            CONTACT FORM
            ============================================================ -->
            <div class="contact-form">
                <h2>Send Us a Message</h2>
                
                <?php if ($success_message): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                
                <?php if ($error_message): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">Your Name *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Your Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.contact-page h1 {
    margin: 30px 0;
}
.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
}
.contact-info {
    background: white;
    padding: 30px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
}
.contact-info h2 {
    margin-bottom: 10px;
}
.contact-info > p {
    color: var(--gray-text);
    margin-bottom: 20px;
}
.info-item {
    display: flex;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid var(--border-color);
}
.info-item:last-child {
    border-bottom: none;
}
.info-item i {
    font-size: 1.5rem;
    color: var(--primary-color);
    width: 40px;
    text-align: center;
}
.info-item h4 {
    margin-bottom: 2px;
}
.info-item p {
    color: var(--gray-text);
}
.contact-form {
    background: white;
    padding: 30px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
}
.contact-form h2 {
    margin-bottom: 20px;
}
.map-container {
    margin-top: 20px;
}
.map-container h3 {
    margin-bottom: 10px;
}
@media (max-width: 768px) {
    .contact-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once '../includes/footer.php'; ?>