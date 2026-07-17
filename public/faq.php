<?php
/**
 * ================================================================
 * FAQ PAGE - E-COMMERCE STORE
 * 
 * Frequently asked questions and answers for customers.
 * ================================================================
 */

$page_title = 'FAQ';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

// Define FAQ data
$faqs = [
    [
        'question' => 'How do I place an order?',
        'answer' => 'Simply browse our products, add items to your cart, and proceed to checkout. Fill in your shipping details and payment method to complete your order.'
    ],
    [
        'question' => 'What payment methods do you accept?',
        'answer' => 'We accept all major credit cards (Visa, MasterCard, Amex), PayPal, and bank transfers.'
    ],
    [
        'question' => 'How long does shipping take?',
        'answer' => 'Orders are typically processed within 1-2 business days. Standard shipping takes 3-5 business days, and express shipping takes 1-2 business days.'
    ],
    [
        'question' => 'Do you offer free shipping?',
        'answer' => 'Yes! We offer free standard shipping on orders over $100.'
    ],
    [
        'question' => 'What is your return policy?',
        'answer' => 'We offer a 30-day return policy on all products. Items must be in original condition with tags attached.'
    ],
    [
        'question' => 'How can I track my order?',
        'answer' => 'Once your order ships, you\'ll receive a tracking number via email. You can also track your order from your dashboard.'
    ],
    [
        'question' => 'Do you offer international shipping?',
        'answer' => 'Yes, we ship to most countries. International shipping rates and times vary by destination.'
    ],
    [
        'question' => 'How do I contact customer support?',
        'answer' => 'You can reach us via the contact form, email, or phone during business hours. Our support team is here to help!'
    ]
];
?>

<div class="container">
    <div class="faq-page">
        <h1>Frequently Asked Questions</h1>
        <p class="faq-subtitle">Find answers to common questions about <?php echo SITE_NAME; ?>.</p>
        
        <!-- ============================================================
        FAQ GRID
        ============================================================ -->
        <div class="faq-grid">
            <?php foreach ($faqs as $index => $faq): ?>
                <div class="card faq-item">
                    <h3><?php echo $faq['question']; ?></h3>
                    <p><?php echo $faq['answer']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- ============================================================
        CONTACT SECTION
        ============================================================ -->
        <div class="faq-contact">
            <h2>Still have questions?</h2>
            <p>Can't find what you're looking for? Contact our support team.</p>
            <a href="contact.php" class="btn-primary">
                <i class="fas fa-envelope"></i> Contact Us
            </a>
        </div>
    </div>
</div>

<style>
.faq-page h1 {
    text-align: center;
    margin-top: 30px;
}
.faq-subtitle {
    text-align: center;
    color: var(--gray-text);
    margin-bottom: 40px;
    font-size: 1.1rem;
}
.faq-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 40px;
}
.faq-item h3 {
    color: var(--secondary-color);
    margin-bottom: 10px;
    font-size: 1.1rem;
}
.faq-item p {
    color: var(--dark-text);
    line-height: 1.6;
}
.faq-contact {
    text-align: center;
    background: white;
    padding: 40px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
}
.faq-contact h2 {
    margin-bottom: 10px;
}
.faq-contact p {
    color: var(--gray-text);
    margin-bottom: 20px;
}
@media (max-width: 768px) {
    .faq-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once '../includes/footer.php'; ?>