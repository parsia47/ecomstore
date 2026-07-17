-- ============================================================
-- DATABASE SCHEMA FOR E-COMMERCE STORE
-- Complete database with 20+ products, categories, and orders
-- ============================================================

-- ------------------------------------------------------------
-- CREATE DATABASE
-- ------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS ecomstore;
USE ecomstore;

-- ------------------------------------------------------------
-- TABLE: users
-- Stores all user accounts (customers and admins)
-- ------------------------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique user ID',
    username VARCHAR(50) UNIQUE NOT NULL COMMENT 'Username for login',
    email VARCHAR(100) UNIQUE NOT NULL COMMENT 'User email address',
    password_hash VARCHAR(255) NOT NULL COMMENT 'Hashed password (bcrypt)',
    full_name VARCHAR(100) NOT NULL COMMENT 'User full name',
    user_type ENUM('customer', 'admin') DEFAULT 'customer' COMMENT 'Account type',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Account status',
    profile_pic VARCHAR(255) COMMENT 'Profile picture filename',
    shipping_address TEXT COMMENT 'Default shipping address',
    phone VARCHAR(20) COMMENT 'Phone number',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Registration date',
    last_login TIMESTAMP COMMENT 'Last login timestamp'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='User accounts table';

-- ------------------------------------------------------------
-- TABLE: categories
-- Product categories for organization
-- ------------------------------------------------------------
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Category ID',
    name VARCHAR(100) NOT NULL COMMENT 'Category name',
    description TEXT COMMENT 'Category description',
    icon VARCHAR(50) COMMENT 'Font Awesome icon class'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Product categories';

-- ------------------------------------------------------------
-- TABLE: products
-- Main products table with 20+ products
-- ------------------------------------------------------------
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Product ID',
    name VARCHAR(255) NOT NULL COMMENT 'Product name',
    description TEXT COMMENT 'Product description',
    price DECIMAL(10,2) NOT NULL COMMENT 'Current price',
    sale_price DECIMAL(10,2) COMMENT 'Sale price (if on sale)',
    category_id INT COMMENT 'Category ID (foreign key)',
    stock_quantity INT DEFAULT 0 COMMENT 'Available stock quantity',
    image VARCHAR(255) COMMENT 'Main product image filename',
    images TEXT COMMENT 'JSON array of additional images',
    rating DECIMAL(3,2) DEFAULT 0 COMMENT 'Average rating (1-5)',
    total_reviews INT DEFAULT 0 COMMENT 'Total number of reviews',
    is_featured BOOLEAN DEFAULT FALSE COMMENT 'Featured product flag',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Product visibility',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Product creation date',
    FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Products catalogue';

-- ------------------------------------------------------------
-- TABLE: product_options
-- Product variations (size, color, etc.)
-- ------------------------------------------------------------
CREATE TABLE product_options (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Option ID',
    product_id INT NOT NULL COMMENT 'Product ID (foreign key)',
    option_name VARCHAR(100) NOT NULL COMMENT 'Option type (Size, Color, etc.)',
    option_value VARCHAR(100) NOT NULL COMMENT 'Option value (S, M, L, Red, etc.)',
    price_adjustment DECIMAL(10,2) DEFAULT 0 COMMENT 'Price adjustment for this option',
    stock_quantity INT DEFAULT 0 COMMENT 'Stock for this specific option',
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Product options/variations';

-- ------------------------------------------------------------
-- TABLE: cart
-- Shopping cart items for logged-in users
-- ------------------------------------------------------------
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Cart item ID',
    user_id INT NOT NULL COMMENT 'User ID (foreign key)',
    product_id INT NOT NULL COMMENT 'Product ID (foreign key)',
    option_id INT COMMENT 'Selected option ID',
    quantity INT DEFAULT 1 COMMENT 'Quantity of product',
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Added to cart timestamp',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (option_id) REFERENCES product_options(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Shopping cart items';

-- ------------------------------------------------------------
-- TABLE: orders
-- Customer orders
-- ------------------------------------------------------------
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Order ID',
    user_id INT NOT NULL COMMENT 'Customer ID (foreign key)',
    order_number VARCHAR(50) UNIQUE NOT NULL COMMENT 'Unique order number',
    total_amount DECIMAL(10,2) NOT NULL COMMENT 'Order total amount',
    shipping_address TEXT NOT NULL COMMENT 'Shipping address for this order',
    payment_method VARCHAR(50) COMMENT 'Payment method used',
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending' COMMENT 'Payment status',
    order_status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending' COMMENT 'Order status',
    notes TEXT COMMENT 'Order notes',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Order date',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update',
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Customer orders';

-- ------------------------------------------------------------
-- TABLE: order_items
-- Individual items within an order
-- ------------------------------------------------------------
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Order item ID',
    order_id INT NOT NULL COMMENT 'Order ID (foreign key)',
    product_id INT NOT NULL COMMENT 'Product ID (foreign key)',
    option_id INT COMMENT 'Selected option ID',
    product_name VARCHAR(255) NOT NULL COMMENT 'Product name at time of purchase',
    option_name VARCHAR(100) COMMENT 'Option name at time of purchase',
    option_value VARCHAR(100) COMMENT 'Option value at time of purchase',
    quantity INT NOT NULL COMMENT 'Quantity ordered',
    price DECIMAL(10,2) NOT NULL COMMENT 'Price at time of purchase',
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Order line items';

-- ------------------------------------------------------------
-- TABLE: reviews
-- Product reviews from customers
-- ------------------------------------------------------------
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Review ID',
    user_id INT NOT NULL COMMENT 'Reviewer ID (foreign key)',
    product_id INT NOT NULL COMMENT 'Product ID (foreign key)',
    order_id INT NOT NULL COMMENT 'Order ID (foreign key)',
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5) COMMENT 'Rating (1-5 stars)',
    review TEXT COMMENT 'Review text',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Review date',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (order_id) REFERENCES orders(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Product reviews';

-- ------------------------------------------------------------
-- TABLE: site_settings
-- Site-wide configuration settings
-- ------------------------------------------------------------
CREATE TABLE site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Setting ID',
    setting_key VARCHAR(50) UNIQUE NOT NULL COMMENT 'Configuration key',
    setting_value TEXT COMMENT 'Configuration value',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Site configuration';

-- ============================================================
-- INSERT SAMPLE DATA
-- ============================================================

-- ------------------------------------------------------------
-- Categories (6 categories)
-- ------------------------------------------------------------
INSERT INTO categories (name, description, icon) VALUES
('Electronics', 'Latest gadgets and electronics', 'fa-laptop'),
('Books', 'Best-selling books and e-books', 'fa-book'),
('Clothing', 'Fashion and apparel', 'fa-tshirt'),
('Home & Garden', 'Home decor and garden supplies', 'fa-home'),
('Sports', 'Sports equipment and gear', 'fa-football'),
('Toys', 'Toys and games for all ages', 'fa-gamepad');

-- ------------------------------------------------------------
-- Products (27 products - exceeds 20 requirement)
-- ------------------------------------------------------------
INSERT INTO products (name, description, price, sale_price, category_id, stock_quantity, image, is_featured, rating, total_reviews) VALUES
-- Electronics (Category 1)
('iPhone 15 Pro', 'Latest iPhone with A17 chip, 48MP camera, titanium design. 6.1-inch display, 256GB storage.', 1099.00, 999.00, 1, 50, 'product1.jpg', 1, 4.8, 120),
('Samsung Galaxy S24', 'Premium Android phone with AI features, 200MP camera, 6.8-inch display, 512GB storage.', 999.00, 899.00, 1, 40, 'product2.jpg', 1, 4.6, 95),
('MacBook Pro 16"', 'Powerful laptop with M3 Pro chip, 36GB RAM, 1TB SSD, 16-inch Liquid Retina display.', 2499.00, NULL, 1, 25, 'product3.jpg', 1, 4.9, 80),
('Dell XPS 15', 'Premium Windows laptop with Intel i9, 32GB RAM, 1TB SSD, 15.6-inch 4K display.', 1899.00, 1699.00, 1, 30, 'product4.jpg', 0, 4.5, 60),
('Sony WH-1000XM5', 'Industry-leading noise cancelling headphones with 30-hour battery life.', 399.00, 349.00, 1, 60, 'product5.jpg', 1, 4.7, 150),

-- Books (Category 2)
('Atomic Habits', 'Bestseller about building good habits and breaking bad ones. Over 10 million copies sold.', 29.99, 19.99, 2, 100, 'product6.jpg', 1, 4.8, 200),
('The Psychology of Money', 'Timeless lessons on wealth, greed, and happiness. New York Times Bestseller.', 27.99, 22.99, 2, 80, 'product7.jpg', 1, 4.7, 170),
('Dune', 'Classic sci-fi novel by Frank Herbert. Winner of the Hugo and Nebula awards.', 18.99, 14.99, 2, 70, 'product8.jpg', 0, 4.5, 190),
('The Alchemist', 'International bestseller about following your dreams. Translated into 80 languages.', 16.99, 12.99, 2, 90, 'product9.jpg', 0, 4.6, 220),
('Sapiens', 'A brief history of humankind. #1 New York Times Bestseller.', 22.99, 18.99, 2, 60, 'product10.jpg', 1, 4.4, 180),

-- Clothing (Category 3)
("Levi's 501 Jeans", 'Classic blue jeans, straight fit, 100% cotton. Available in multiple sizes.', 89.99, 69.99, 3, 100, 'product11.jpg', 1, 4.3, 130),
('Nike Air Max', 'Comfortable running shoes with Air cushioning. Breathable mesh upper.', 149.99, 129.99, 3, 50, 'product12.jpg', 1, 4.6, 160),
('Columbia Jacket', 'Waterproof winter jacket for extreme conditions. 100% polyester insulation.', 199.99, 159.99, 3, 35, 'product13.jpg', 0, 4.4, 90),
('Adidas Hoodie', 'Comfortable cotton hoodie with classic design. Regular fit, fleece lining.', 69.99, 49.99, 3, 75, 'product14.jpg', 0, 4.2, 110),
('Ray-Ban Sunglasses', 'Classic wayfarer sunglasses with UV protection. Polarized lenses.', 159.99, 129.99, 3, 45, 'product15.jpg', 1, 4.5, 140),

-- Home & Garden (Category 4)
('IKEA Sofa', 'Modern 3-seater sofa with storage. Fabric upholstery, solid wood frame.', 599.00, 499.00, 4, 15, 'product16.jpg', 1, 4.2, 75),
('Dyson Vacuum', 'Cordless vacuum with powerful suction. Up to 60 minutes runtime.', 499.99, 399.99, 4, 20, 'product17.jpg', 1, 4.8, 200),
('Philips Hue Lights', 'Smart LED lights with color control. Compatible with Alexa and Google Home.', 199.99, 149.99, 4, 40, 'product18.jpg', 0, 4.3, 120),
('Cast Iron Skillet', 'Pre-seasoned cast iron skillet. 12-inch diameter, oven-safe.', 49.99, 39.99, 4, 80, 'product19.jpg', 0, 4.6, 160),
('Garden Tool Set', 'Complete set of gardening tools. Includes trowel, pruner, gloves, and more.', 79.99, 59.99, 4, 30, 'product20.jpg', 0, 4.1, 85),

-- Sports (Category 5)
('Basketball', 'Official size basketball for indoor/outdoor. Durable rubber construction.', 29.99, 24.99, 5, 60, 'product21.jpg', 1, 4.4, 100),
('Yoga Mat', 'Non-slip yoga mat with carrying strap. 6mm thickness, eco-friendly material.', 39.99, 29.99, 5, 50, 'product22.jpg', 0, 4.3, 90),
('Dumbbell Set', 'Adjustable dumbbell set, 5-50 lbs. Quick-select mechanism.', 199.99, 149.99, 5, 25, 'product23.jpg', 0, 4.7, 70),
('Tennis Racket', 'Professional tennis racket with bag. Lightweight graphite frame.', 89.99, 69.99, 5, 30, 'product24.jpg', 0, 4.2, 55),

-- Toys (Category 6)
('LEGO Star Wars Set', 'Star Wars building kit, 1000+ pieces. Includes 5 minifigures.', 149.99, 119.99, 6, 40, 'product25.jpg', 1, 4.9, 250),
('Board Game Collection', 'Set of 5 classic board games. Perfect for family game night.', 49.99, 39.99, 6, 35, 'product26.jpg', 0, 4.3, 110),
('Remote Control Car', 'Off-road RC car with rechargeable battery. 2.4GHz remote control.', 59.99, 49.99, 6, 45, 'product27.jpg', 0, 4.1, 65);

-- ------------------------------------------------------------
-- Product Options (for clothing items)
-- ------------------------------------------------------------
INSERT INTO product_options (product_id, option_name, option_value, price_adjustment, stock_quantity) VALUES
-- Levi's Jeans (product 11)
(11, 'Size', 'S', 0, 25),
(11, 'Size', 'M', 0, 30),
(11, 'Size', 'L', 0, 25),
(11, 'Size', 'XL', 0, 20),
-- Nike Air Max (product 12)
(12, 'Size', '8', 0, 15),
(12, 'Size', '9', 0, 20),
(12, 'Size', '10', 0, 15),
-- Columbia Jacket (product 13)
(13, 'Size', 'S', 0, 10),
(13, 'Size', 'M', 0, 15),
(13, 'Size', 'L', 0, 10),
-- Adidas Hoodie (product 14)
(14, 'Size', 'S', 0, 25),
(14, 'Size', 'M', 0, 30),
(14, 'Size', 'L', 0, 20),
-- Ray-Ban Sunglasses (product 15)
(15, 'Color', 'Black', 0, 20),
(15, 'Color', 'Brown', 0, 15),
(15, 'Color', 'Tortoise', 10, 10);

-- ------------------------------------------------------------
-- Sample Users
-- ------------------------------------------------------------
INSERT INTO users (username, email, password_hash, full_name, user_type, is_active, shipping_address, phone) VALUES
('admin', 'admin@ecomstore.com', '$2y$10$YourHashedPasswordHere', 'System Administrator', 'admin', 1, '123 Admin St, City, State 12345', '+1-555-000-0000'),
('john_doe', 'john@email.com', '$2y$10$YourHashedPasswordHere', 'John Doe', 'customer', 1, '456 Main St, Toronto, ON M5V 2H1', '+1-555-111-1111'),
('jane_smith', 'jane@email.com', '$2y$10$YourHashedPasswordHere', 'Jane Smith', 'customer', 1, '789 Oak Ave, Vancouver, BC V6Z 2E6', '+1-555-222-2222');

-- ------------------------------------------------------------
-- Site Settings
-- ------------------------------------------------------------
INSERT INTO site_settings (setting_key, setting_value) VALUES
('active_template', 'template1'),
('site_name', 'EcomStore'),
('maintenance_mode', 'false'),
('contact_email', 'info@ecomstore.com'),
('currency_symbol', '$'),
('tax_rate', '13.00');