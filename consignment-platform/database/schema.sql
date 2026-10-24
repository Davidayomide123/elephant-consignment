-- database/schema.sql
CREATE DATABASE IF NOT EXISTS consignment_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE consignment_db;

-- 1. Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    display_name VARCHAR(100) NOT NULL,
    role ENUM('consignor', 'admin') NOT NULL DEFAULT 'consignor',
    paypal_email VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Items Table
CREATE TABLE IF NOT EXISTS items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    consignor_id INT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(100) NOT NULL,
    `condition` ENUM('new', 'like_new', 'good', 'fair') NOT NULL,
    asking_price DECIMAL(10,2) NOT NULL,
    commission_rate DECIMAL(5,2) NOT NULL DEFAULT 50.00,
    status ENUM('pending', 'approved', 'rejected', 'sold', 'returned') NOT NULL DEFAULT 'pending',
    rejection_reason TEXT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    approved_at TIMESTAMP NULL,
    sold_at TIMESTAMP NULL,
    sale_price DECIMAL(10,2) NULL,
    sku VARCHAR(50) UNIQUE,
    FOREIGN KEY (consignor_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 3. Item Images Table
CREATE TABLE IF NOT EXISTS item_images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    item_id INT UNSIGNED NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    sort_order TINYINT UNSIGNED DEFAULT 0,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. Payouts Table
CREATE TABLE IF NOT EXISTS payouts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    consignor_id INT UNSIGNED NOT NULL,
    item_id INT UNSIGNED NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'paid') NOT NULL DEFAULT 'pending',
    paid_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (consignor_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5. Activity Log Table
CREATE TABLE IF NOT EXISTS activity_log (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50),
    entity_id INT UNSIGNED,
    metadata JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Seed an initial Admin User
-- Email: admin@elephant.com | Password: ChangeMe123!
INSERT INTO users (email, password_hash, display_name, role) 
VALUES (
    'admin@elephant.com', 
    '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 
    'Elephant Admin', 
    'admin'
) ON DUPLICATE KEY UPDATE email=email;

CREATE TABLE shipments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tracking_code VARCHAR(50) UNIQUE NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    status ENUM('pending', 'in_transit', 'customs', 'delivered') DEFAULT 'pending',
    origin_port VARCHAR(100),
    destination_port VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE shipment_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    shipment_id INT UNSIGNED NOT NULL,
    location VARCHAR(255) NOT NULL,
    status_message TEXT NOT NULL,
    is_alert BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (shipment_id) REFERENCES shipments(id) ON DELETE CASCADE
);

USE consignment_db;

CREATE TABLE IF NOT EXISTS shipments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tracking_code VARCHAR(50) UNIQUE NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    status ENUM('pending', 'in_transit', 'customs', 'delivered') DEFAULT 'in_transit',
    origin_port VARCHAR(100) NOT NULL,
    destination_port VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS shipment_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    shipment_id INT UNSIGNED NOT NULL,
    location VARCHAR(255) NOT NULL,
    status_message TEXT NOT NULL,
    is_alert BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (shipment_id) REFERENCES shipments(id) ON DELETE CASCADE
);