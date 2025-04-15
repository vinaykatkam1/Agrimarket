-- Create database
CREATE DATABASE IF NOT EXISTS farmer_marketplace;
USE farmer_marketplace;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample users
INSERT INTO users (name, email, password, created_at) VALUES
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW()),
('Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW()),
('Farmer Kumar', 'kumar@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW());

-- Create suppliers table first (before products)
CREATE TABLE suppliers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT,
    logo_url VARCHAR(255),
    rating DECIMAL(3,2) DEFAULT 0.00,
    reviews_count INT DEFAULT 0,
    established_year INT NOT NULL,
    location VARCHAR(255) NOT NULL,
    website VARCHAR(255),
    business_type ENUM('manufacturer', 'distributor', 'retailer', 'wholesaler') NOT NULL,
    certifications TEXT,
    minimum_order_amount DECIMAL(10,2) DEFAULT 0.00,
    delivery_time VARCHAR(100) NOT NULL,
    payment_terms TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample suppliers with complete information
INSERT INTO suppliers (name, category, description, logo_url, rating, reviews_count, established_year, location, website, business_type, certifications, minimum_order_amount, delivery_time, payment_terms) VALUES
('Green Harvest Co.', 'seeds', 'Leading supplier of organic and heirloom seeds with over 20 years of experience.', '../images/suppliers/green-harvest.jpg', 4.8, 156, 2000, 'California, USA', 'https://greenharvest.com', 'manufacturer', 'USDA Organic, ISO 9001', 50.00, '3-5 business days', 'Net 30'),
('FertilEarth', 'fertilizer', 'Premium organic and synthetic fertilizers for all types of crops.', '../images/suppliers/fertil-earth.jpg', 4.6, 98, 2010, 'Texas, USA', 'https://fertilearth.com', 'manufacturer', 'ISO 9001, Organic Certified', 100.00, '2-4 business days', 'Net 15'),
('AgriTech Solutions', 'equipment', 'Modern farming equipment and technology solutions for efficient agriculture.', '../images/suppliers/agritech.jpg', 4.7, 203, 2015, 'Illinois, USA', 'https://agritech.com', 'distributor', 'ISO 9001, CE Certified', 200.00, '1-3 business days', 'Net 30'),
('Seed Masters', 'seeds', 'Specializing in high-yield crop seeds and expert growing advice.', '../images/suppliers/seed-masters.jpg', 4.5, 167, 2005, 'Iowa, USA', 'https://seedmasters.com', 'wholesaler', 'USDA Certified', 75.00, '2-5 business days', 'Net 15'),
('Organic Plus', 'fertilizer', 'Natural and organic fertilizers for sustainable farming practices.', '../images/suppliers/organic-plus.jpg', 4.9, 89, 2012, 'Oregon, USA', 'https://organicplus.com', 'retailer', 'USDA Organic, Fair Trade', 25.00, '3-6 business days', 'Net 30');

-- Create supplier_contact table
CREATE TABLE supplier_contact (
    id INT PRIMARY KEY AUTO_INCREMENT,
    supplier_id INT NOT NULL,
    contact_person VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    alternate_phone VARCHAR(20),
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    country VARCHAR(100) NOT NULL,
    pincode VARCHAR(20) NOT NULL,
    is_primary BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
);

-- Insert supplier contact information
INSERT INTO supplier_contact (supplier_id, contact_person, email, phone, alternate_phone, address, city, state, country, pincode, is_primary) VALUES
(1, 'John Smith', 'john@greenharvest.com', '9963039884', '+1-555-123-4568', '123 Farm Lane', 'Sacramento', 'California', 'USA', '94203', 1),
(2, 'Sarah Johnson', 'sarah@fertilearth.com', '+1-555-234-5678', '+1-555-234-5679', '456 Agriculture Ave', 'Houston', 'Texas', 'USA', '77001', 1),
(3, 'Michael Brown', 'michael@agritech.com', '+1-555-345-6789', '+1-555-345-6790', '789 Tech Park', 'Chicago', 'Illinois', 'USA', '60601', 1),
(4, 'Emily Davis', 'emily@seedmasters.com', '+1-555-456-7890', '+1-555-456-7891', '321 Seed Street', 'Des Moines', 'Iowa', 'USA', '50301', 1),
(5, 'David Wilson', 'david@organicplus.com', '+1-555-567-8901', '+1-555-567-8902', '654 Organic Way', 'Portland', 'Oregon', 'USA', '97201', 1);

-- Create supplier_social_media table
CREATE TABLE supplier_social_media (
    id INT PRIMARY KEY AUTO_INCREMENT,
    supplier_id INT NOT NULL,
    platform VARCHAR(50) NOT NULL,
    url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id)
);

-- Create supplier_products table (to show supplier's product catalog)
CREATE TABLE supplier_products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    supplier_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(100) NOT NULL,
    price DECIMAL(10,2),
    minimum_order_quantity INT,
    unit VARCHAR(20),
    image_url VARCHAR(255),
    is_featured BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id)
);

-- Create products table (after suppliers)
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    category ENUM('organic', 'pesticides', 'fertilizers', 'seeds') NOT NULL,
    image_url VARCHAR(255),
    stock INT NOT NULL DEFAULT 0,
    supplier_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id)
);

-- Insert sample products
INSERT INTO products (name, description, price, category, image_url, stock, supplier_id) VALUES
('Dr. Earth Premium Gold', 'Organic All Purpose Fertilizer (4-4-4) with TruBiotic Inside', 24.99, 'fertilizers', 'images/fertilizers/dr-earth-fertilizer.jpg', 100, 2),
('Growise Organic Compost', 'Peat-free vegetable compost for organic gardening', 19.99, 'organic', 'images/organic/organic-compost-new.jpg', 150, 5),
('Chadwick Cherry Tomato Seeds', 'Organic Heirloom Tomato Seeds - Non-GMO, USDA Certified', 4.99, 'seeds', 'images/seeds/tomato-seeds.jpg', 500, 1),
('IFFCO NPK Fertilizer', 'Premium NPK 10-26-26 Blend for Enhanced Growth', 29.99, 'fertilizers', 'images/fertilizers/npk.jpg', 200, 2),
('Organic Tomato Seeds', 'High-quality organic tomato seeds for better yield', 199.99, 'organic', 'images/products/tomato-seeds.jpg', 100, 1),
('Natural Fertilizer', 'Eco-friendly natural fertilizer for all crops', 499.99, 'fertilizers', 'images/fertilizers/natural-fertilizer.jpg', 50, 2),
('Bio Pesticide', 'Safe and effective organic pesticide', 299.99, 'pesticides', 'images/products/bio-pesticide.jpg', 75, 3),
('Hybrid Rice Seeds', 'High-yielding hybrid rice seeds', 399.99, 'seeds', 'images/seeds/rice-seed.webp', 200, 4),
('Organic Compost', 'Rich organic compost for better soil health', 599.99, 'organic', 'images/organic/organic-compost-new.jpg', 30, 5),
('Premium NPK Fertilizer', 'Balanced NPK fertilizer for all crops', 799.99, 'fertilizers', 'images/fertilizers/premium-npk.jpg', 60, 2);

-- Create orders table
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    order_number VARCHAR(20) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    discount_amount DECIMAL(10,2) DEFAULT 0.00,
    final_amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('online', 'cod') NOT NULL,
    payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    order_status ENUM('pending', 'confirmed', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    promo_code VARCHAR(20),
    shipping_address TEXT NOT NULL,
    shipping_city VARCHAR(100) NOT NULL,
    shipping_pincode VARCHAR(10) NOT NULL,
    contact_phone VARCHAR(15) NOT NULL,
    expected_delivery_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create order_items table
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price_per_unit DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Create promo_codes table
CREATE TABLE promo_codes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(20) NOT NULL UNIQUE,
    discount_amount DECIMAL(10,2),
    discount_percentage INT,
    min_order_amount DECIMAL(10,2) DEFAULT 0.00,
    max_discount_amount DECIMAL(10,2),
    valid_from TIMESTAMP NOT NULL,
    valid_until TIMESTAMP NOT NULL,
    is_active BOOLEAN DEFAULT true,
    usage_limit INT,
    times_used INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample promo codes
INSERT INTO promo_codes (code, discount_percentage, min_order_amount, max_discount_amount, valid_from, valid_until, usage_limit) VALUES
('FIRST20', 20, 1000.00, 2000.00, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 100),
('SAVE500', NULL, 2500.00, 500.00, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 50),
('FARMER10', 10, 500.00, 1000.00, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 200);

-- Create cart table
CREATE TABLE cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Create supplier_reviews table
CREATE TABLE supplier_reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    supplier_id INT NOT NULL,
    user_id INT NOT NULL,
    rating DECIMAL(2,1) NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create order_tracking table
CREATE TABLE order_tracking (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    status VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    location VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

-- Insert sample supplier reviews
INSERT INTO supplier_reviews (supplier_id, user_id, rating, comment) VALUES
(1, 1, 5, 'Excellent quality seeds, great germination rate!'),
(1, 2, 4, 'Good products but shipping could be faster'),
(2, 1, 5, 'Best fertilizers in the market, saw results within weeks'),
(3, 2, 4, 'Very effective equipment, good after-sales service'),
(4, 1, 5, 'High-quality seeds, excellent customer support'),
(5, 2, 5, 'Amazing organic products, will buy again');

-- Create supplier_certifications table
CREATE TABLE supplier_certifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    supplier_id INT NOT NULL,
    certification_name VARCHAR(255) NOT NULL,
    issuing_authority VARCHAR(255) NOT NULL,
    issue_date DATE NOT NULL,
    expiry_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
);

-- Create supplier_achievements table
CREATE TABLE supplier_achievements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    supplier_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    year INT NOT NULL,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
);

-- Create supplier_gallery table
CREATE TABLE supplier_gallery (
    id INT PRIMARY KEY AUTO_INCREMENT,
    supplier_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    caption VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
);

-- Create supplier_faqs table
CREATE TABLE supplier_faqs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    supplier_id INT NOT NULL,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
);

-- Create supplier_testimonials table
CREATE TABLE supplier_testimonials (
    id INT PRIMARY KEY AUTO_INCREMENT,
    supplier_id INT NOT NULL,
    user_id INT NOT NULL,
    rating DECIMAL(2,1) NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create supplier_shipping table
CREATE TABLE supplier_shipping (
    id INT PRIMARY KEY AUTO_INCREMENT,
    supplier_id INT NOT NULL,
    shipping_method VARCHAR(100) NOT NULL,
    delivery_time VARCHAR(100) NOT NULL,
    shipping_cost DECIMAL(10,2) NOT NULL,
    free_shipping_threshold DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
);

-- Create supplier_payment_methods table
CREATE TABLE supplier_payment_methods (
    id INT PRIMARY KEY AUTO_INCREMENT,
    supplier_id INT NOT NULL,
    payment_method VARCHAR(100) NOT NULL,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
);

-- Insert sample data for new tables
INSERT INTO supplier_certifications (supplier_id, certification_name, issuing_authority, issue_date, expiry_date)
VALUES 
(1, 'Organic Certification', 'USDA', '2022-01-15', '2025-01-15'),
(1, 'ISO 9001:2015', 'ISO', '2021-06-20', '2024-06-20'),
(2, 'Fair Trade Certified', 'Fair Trade USA', '2022-03-10', '2025-03-10');

INSERT INTO supplier_achievements (supplier_id, title, description, year, image_url)
VALUES 
(1, 'Best Organic Supplier 2022', 'Awarded for excellence in organic farming practices', 2022, 'images/achievements/best-organic-2022.jpg'),
(2, 'Sustainable Business Award', 'Recognized for sustainable business practices', 2021, 'images/achievements/sustainable-award-2021.jpg');

INSERT INTO supplier_gallery (supplier_id, image_url, caption)
VALUES 
(1, 'images/gallery/farm-1.jpg', 'Our main farm facility'),
(1, 'images/gallery/harvest-1.jpg', 'Harvest season 2022'),
(2, 'images/gallery/processing-1.jpg', 'Modern processing facility');

INSERT INTO supplier_faqs (supplier_id, question, answer)
VALUES 
(1, 'What is your minimum order quantity?', 'Our minimum order quantity varies by product. Please contact us for specific details.'),
(1, 'Do you offer international shipping?', 'Yes, we offer international shipping to most countries. Shipping costs and times vary by destination.'),
(2, 'Are your products organic certified?', 'Yes, all our products are certified organic by USDA.');

INSERT INTO supplier_shipping (supplier_id, shipping_method, delivery_time, shipping_cost, free_shipping_threshold)
VALUES 
(1, 'Standard Shipping', '3-5 business days', 5.99, 100.00),
(1, 'Express Shipping', '1-2 business days', 12.99, 200.00),
(2, 'Standard Shipping', '2-4 business days', 4.99, 150.00);

INSERT INTO supplier_payment_methods (supplier_id, payment_method, details)
VALUES 
(1, 'Credit Card', 'Visa, MasterCard, American Express'),
(1, 'Bank Transfer', 'Direct bank transfer available'),
(2, 'PayPal', 'Secure PayPal payments accepted'),
(2, 'Credit Card', 'All major credit cards accepted');

-- Update existing suppliers with new information
UPDATE suppliers 
SET 
    established_year = 2010,
    location = 'California, USA',
    website = 'https://greenharvest.com',
    business_type = 'manufacturer',
    certifications = 'USDA Organic, ISO 9001',
    minimum_order_amount = 50.00,
    delivery_time = '3-5 business days',
    payment_terms = 'Net 30'
WHERE id = 1;

UPDATE suppliers 
SET 
    established_year = 2015,
    location = 'Oregon, USA',
    website = 'https://fertilearth.com',
    business_type = 'distributor',
    certifications = 'Fair Trade Certified',
    minimum_order_amount = 100.00,
    delivery_time = '2-4 business days',
    payment_terms = 'Net 15'
WHERE id = 2;

-- Create supplier contact requests table
CREATE TABLE IF NOT EXISTS supplier_contact_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create supplier quote requests table
CREATE TABLE IF NOT EXISTS supplier_quote_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    product_details TEXT NOT NULL,
    requirements TEXT,
    status ENUM('pending', 'processing', 'completed', 'rejected') DEFAULT 'pending',
    created_at DATETIME NOT NULL,
    updated_at DATETIME,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; 