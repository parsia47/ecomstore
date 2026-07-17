/**
 * ================================================================
 * MAIN JAVASCRIPT - E-COMMERCE STORE
 * 
 * This file contains all client-side JavaScript functionality.
 * Includes navigation, cart operations, search, filters, and more.
 * ================================================================
 */

// Wait for DOM to be fully loaded before executing
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    // ============================================================
    // SECTION 1: MOBILE NAVIGATION
    // ============================================================
    
    // Get the navigation toggle button and menu
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    // Toggle mobile menu when hamburger icon is clicked
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function(e) {
            e.preventDefault();
            // Toggle active class for menu visibility
            navMenu.classList.toggle('active');
            // Toggle active class for hamburger animation
            this.classList.toggle('active');
            // Update accessibility attribute
            this.setAttribute('aria-expanded', navMenu.classList.contains('active'));
        });
    }
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (navMenu && navMenu.classList.contains('active')) {
            if (!e.target.closest('.navbar')) {
                navMenu.classList.remove('active');
                if (navToggle) {
                    navToggle.classList.remove('active');
                    navToggle.setAttribute('aria-expanded', 'false');
                }
            }
        }
    });
    
    // ============================================================
    // SECTION 2: SEARCH FUNCTIONALITY
    // ============================================================
    
    // Handle product search form submission
    const searchForm = document.getElementById('product-search');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                const searchTerm = searchInput.value.trim();
                if (searchTerm) {
                    // Redirect to products page with search query
                    window.location.href = '/ecomstore/public/products.php?search=' + 
                        encodeURIComponent(searchTerm);
                }
            }
        });
    }
    
    // ============================================================
    // SECTION 3: ADD TO CART (AJAX)
    // ============================================================
    
    // Handle all "Add to Cart" button clicks
    document.querySelectorAll('.add-to-cart').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get product ID from data attribute
            const productId = this.dataset.productId;
            
            // Get quantity from input field if it exists
            let quantity = 1;
            const qtyInput = document.getElementById('quantity-' + productId);
            if (qtyInput) {
                quantity = parseInt(qtyInput.value) || 1;
            }
            
            // Send AJAX request to add item to cart
            fetch('/ecomstore/public/cart.php?action=add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_id=' + productId + '&quantity=' + quantity
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    // Update cart badge with new count
                    const badge = document.querySelector('.cart-badge');
                    if (badge) {
                        badge.textContent = data.cart_count;
                    }
                    // Show success message
                    alert('Product added to cart!');
                } else {
                    // Show error message
                    alert(data.message || 'Error adding to cart');
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                // If user is not logged in, redirect to login
                alert('Please login to add items to cart.');
            });
        });
    });
    
    // ============================================================
    // SECTION 4: UPDATE CART QUANTITY
    // ============================================================
    
    // Handle quantity update buttons in cart
    document.querySelectorAll('.update-cart').forEach(function(button) {
        button.addEventListener('click', function() {
            const cartId = this.dataset.cartId;
            const qtyInput = document.getElementById('qty-' + cartId);
            
            if (qtyInput) {
                const quantity = parseInt(qtyInput.value) || 1;
                
                // Send AJAX request to update quantity
                fetch('/ecomstore/public/cart.php?action=update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'cart_id=' + cartId + '&quantity=' + quantity
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    if (data.success) {
                        // Reload page to show updated cart
                        location.reload();
                    } else {
                        alert('Error updating cart');
                    }
                });
            }
        });
    });
    
    // ============================================================
    // SECTION 5: REMOVE FROM CART
    // ============================================================
    
    // Handle remove from cart buttons
    document.querySelectorAll('.remove-from-cart').forEach(function(button) {
        button.addEventListener('click', function() {
            // Confirm before removing
            if (confirm('Remove this item from cart?')) {
                const cartId = this.dataset.cartId;
                
                // Send AJAX request to remove item
                fetch('/ecomstore/public/cart.php?action=remove', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'cart_id=' + cartId
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    if (data.success) {
                        // Reload page to show updated cart
                        location.reload();
                    } else {
                        alert('Error removing item');
                    }
                });
            }
        });
    });
    
    // ============================================================
    // SECTION 6: PRODUCT FILTERING
    // ============================================================
    
    // Handle category filter buttons
    document.querySelectorAll('.filter-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const category = this.dataset.category;
            
            // Update active button state
            document.querySelectorAll('.filter-btn').forEach(function(btn) {
                btn.classList.remove('active');
            });
            this.classList.add('active');
            
            // Filter products based on category
            document.querySelectorAll('.product-card').forEach(function(card) {
                if (category === 'all' || card.dataset.category === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
    
    // ============================================================
    // SECTION 7: PRODUCT RATING
    // ============================================================
    
    // Handle star rating input for reviews
    document.querySelectorAll('.rating-input').forEach(function(input) {
        input.addEventListener('change', function() {
            const rating = parseInt(this.value);
            const container = this.closest('.rating-container');
            if (container) {
                // Update star display based on selected rating
                const stars = container.querySelectorAll('.star');
                stars.forEach(function(star, index) {
                    if (index < rating) {
                        star.classList.add('active');
                        star.textContent = '★';
                    } else {
                        star.classList.remove('active');
                        star.textContent = '☆';
                    }
                });
            }
        });
    });
    
    // ============================================================
    // SECTION 8: AUTO-DISMISS ALERTS
    // ============================================================
    
    // Auto-dismiss alerts after 5 seconds
    document.querySelectorAll('.alert').forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.style.display = 'none';
            }, 500);
        }, 5000);
    });
    
    // ============================================================
    // SECTION 9: INTERACTIVE MAP (Leaflet)
    // ============================================================
    
    // Initialize the store location map
    const mapContainer = document.getElementById('store-map');
    if (mapContainer && typeof L !== 'undefined') {
        // Create map centered on Windsor, ON
        const map = L.map('store-map').setView([42.3149, -83.0364], 13);
        
        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Add a marker at the store location
        L.marker([42.3149, -83.0364])
            .bindPopup('<strong>EcomStore Headquarters</strong><br>Windsor, ON')
            .addTo(map);
    }
    
    // ============================================================
    // SECTION 10: DATA VISUALIZATION (Chart.js)
    // ============================================================
    
    // Create sales chart if data is available
    const chartData = document.getElementById('sales-chart-data');
    if (chartData && typeof Chart !== 'undefined') {
        let data = [];
        try {
            data = JSON.parse(chartData.dataset.chart || '[]');
        } catch(e) {
            console.error('Error parsing chart data:', e);
        }
        
        if (data.length > 0) {
            const ctx = document.getElementById('sales-chart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map(function(item) { return item.month; }),
                        datasets: [{
                            label: 'Sales ($)',
                            data: data.map(function(item) { return item.sales; }),
                            backgroundColor: 'rgba(255, 107, 53, 0.6)',
                            borderColor: 'rgba(255, 107, 53, 1)',
                            borderWidth: 2,
                            borderRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            title: {
                                display: true,
                                text: 'Monthly Sales'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        }
    }
});

// ================================================================
// SECTION 11: HELPER FUNCTIONS
// ================================================================

/**
 * Format a price for display
 * @param {number|string} price - Price value
 * @returns {string} Formatted price with currency symbol
 */
function formatPrice(price) {
    return '$' + parseFloat(price).toFixed(2);
}

/**
 * Update the cart badge count
 * @param {number} count - New cart count
 */
function updateCartBadge(count) {
    const badge = document.querySelector('.cart-badge');
    if (badge) {
        badge.textContent = count;
    }
}