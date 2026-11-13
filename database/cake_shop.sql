-- Luxe Cake Database Schema
-- Database: cake_shop

CREATE DATABASE IF NOT EXISTS `cake_shop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cake_shop`;

-- Users Table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `address` TEXT DEFAULT NULL,
    `role` ENUM('user', 'admin') DEFAULT 'user',
    `is_verified` TINYINT(1) DEFAULT 0,
    `verification_token` VARCHAR(64) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Categories Table
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Cakes Table
CREATE TABLE IF NOT EXISTS `cakes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(150) NOT NULL,
    `description` TEXT NOT NULL,
    `flavor` VARCHAR(100) NOT NULL,
    `price` DECIMAL(10, 2) NOT NULL,
    `weight_options` VARCHAR(100) DEFAULT '0.5kg, 1kg, 2kg, 3kg',
    `image` VARCHAR(255) NOT NULL,
    `is_featured` TINYINT(1) DEFAULT 0,
    `is_available` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Orders Table
CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_number` VARCHAR(30) NOT NULL UNIQUE,
    `user_id` INT NOT NULL,
    `total_amount` DECIMAL(10, 2) NOT NULL,
    `payment_method` VARCHAR(50) DEFAULT 'Cash on Delivery',
    `payment_status` ENUM('Pending', 'Paid', 'Failed') DEFAULT 'Pending',
    `order_status` ENUM('Pending', 'Preparing', 'Baking', 'Out for Delivery', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    `delivery_name` VARCHAR(100) NOT NULL,
    `delivery_phone` VARCHAR(20) NOT NULL,
    `delivery_address` TEXT NOT NULL,
    `delivery_date` DATE NOT NULL,
    `delivery_time_slot` VARCHAR(50) NOT NULL,
    `special_instructions` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Order Items Table
CREATE TABLE IF NOT EXISTS `order_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `cake_id` INT NOT NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    `weight` VARCHAR(50) DEFAULT '1kg',
    `price` DECIMAL(10, 2) NOT NULL,
    `custom_message` VARCHAR(100) DEFAULT NULL,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`cake_id`) REFERENCES `cakes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Data: Categories
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`) VALUES
(1, 'Signature Truffles', 'signature-truffles', 'Decadent chocolate layers infused with artisan ganache.', 'images/cakes/chocolate-truffle.svg'),
(2, 'Artisan Cheesecakes', 'artisan-cheesecakes', 'Rich baked New York style and berry compote cheesecakes.', 'images/cakes/berry-cheesecake.svg'),
(3, 'Celebration & Birthday', 'celebration-birthday', 'Joyful handcrafted cakes tailored for unforgettable milestones.', 'images/cakes/red-velvet.svg'),
(4, 'Luxury Wedding Tiered', 'luxury-wedding-tiered', 'Elegantly sculpted tiered cakes with edible botanical accents.', 'images/cakes/vanilla-rose.svg')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- Seed Data: Default Admin & Customer Users
-- Default Admin Password: admin123 (bcrypt hashed)
-- Default User Password: user123 (bcrypt hashed)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `is_verified`, `verification_token`) VALUES
(1, 'Luxe Administrator', 'admin@luxecake.com', '$2y$10$wTfqT8R6aRjF2tTzBf3gIuDk2f7u9j9L0p/N3t.q7zQ1rZzZ4uF6a', '+91 9876543210', 'admin', 1, NULL),
(2, 'Sarah Jenkins', 'sarah@example.com', '$2y$10$wTfqT8R6aRjF2tTzBf3gIuDk2f7u9j9L0p/N3t.q7zQ1rZzZ4uF6a', '+91 9876543211', 'user', 1, NULL)
ON DUPLICATE KEY UPDATE `email`=VALUES(`email`);

-- Seed Data: Cakes
INSERT INTO `cakes` (`id`, `category_id`, `name`, `slug`, `description`, `flavor`, `price`, `weight_options`, `image`, `is_featured`, `is_available`) VALUES
(1, 1, 'Dark Chocolate Belgian Truffle', 'dark-chocolate-belgian-truffle', 'Layers of 70% dark Belgian cocoa sponge coated in velvety ganache and cocoa nibs.', 'Belgian Dark Chocolate', 45.00, '0.5kg, 1kg, 2kg, 3kg', 'images/cakes/chocolate-truffle.svg', 1, 1),
(2, 2, 'Wild Berry New York Cheesecake', 'wild-berry-new-york-cheesecake', 'Classic baked Philadelphia cream cheese atop a butter biscuit crumb with fresh raspberry compote.', 'Berry & Vanilla Cream', 52.00, '0.5kg, 1kg, 2kg', 'images/cakes/berry-cheesecake.svg', 1, 1),
(3, 3, 'Royal Red Velvet & Mascarpone', 'royal-red-velvet-mascarpone', 'Tender crimson cocoa sponge layered with fluffy cream cheese frosting and white chocolate pearls.', 'Red Velvet & Sweet Cream', 48.00, '0.5kg, 1kg, 2kg, 3kg', 'images/cakes/red-velvet.svg', 1, 1),
(4, 4, 'French Vanilla Rose Elegance', 'french-vanilla-rose-elegance', 'Madagascar bourbon vanilla sponge infused with organic rosewater essence and edible gold leaf.', 'Vanilla & Floral Rose', 58.00, '1kg, 2kg, 3kg, 5kg', 'images/cakes/vanilla-rose.svg', 1, 1),
(5, 1, 'Black Forest Gateau Royale', 'black-forest-gateau-royale', 'Kirsch-soaked chocolate sponge with tart Morello cherries and Swiss whipped cream.', 'Chocolate Cherry Kirsch', 42.00, '0.5kg, 1kg, 2kg', 'images/cakes/black-forest.svg', 0, 1),
(6, 3, 'Salted Caramel Pecan Drip', 'salted-caramel-pecan-drip', 'Moist caramel cake stacked with toasted Georgia pecans and buttery fleur de sel drizzle.', 'Salted Caramel Buttercream', 49.00, '0.5kg, 1kg, 2kg', 'images/cakes/caramel-drip.svg', 1, 1)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);
