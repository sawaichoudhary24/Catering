-- Catering Website Database
-- Database name: catering_db
-- Import this file in phpMyAdmin

CREATE DATABASE IF NOT EXISTS `catering_db`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `catering_db`;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `contact_messages`;
DROP TABLE IF EXISTS `bookings`;
DROP TABLE IF EXISTS `gallery`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(30) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `role` ENUM('user','admin') NOT NULL DEFAULT 'user',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `categories` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_categories_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `products` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `stock` INT NOT NULL DEFAULT 0,
  `image` VARCHAR(255) DEFAULT NULL,
  `category_name` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_products_category` (`category_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `services` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `gallery` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `image` VARCHAR(255) NOT NULL,
  `title` VARCHAR(150) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `bookings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `event_type` VARCHAR(100) NOT NULL,
  `event_date` DATE NOT NULL,
  `guests` INT NOT NULL DEFAULT 1,
  `message` TEXT DEFAULT NULL,
  `status` ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_bookings_status` (`status`),
  KEY `idx_bookings_date` (`event_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `contact_messages` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(30) DEFAULT NULL,
  `message` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin account
-- Email: admin@gmail.com
-- Password: admin123
INSERT INTO `users`
(`name`, `email`, `password`, `phone`, `address`, `role`)
VALUES
('Administrator', 'admin@gmail.com',
 '$2y$12$5hFGdkA72pKCefXhKUxfUe4Y9s8aPVARVQOhzM0APjQYYylBbFkPO',
 NULL, NULL, 'admin');

-- Demo user
INSERT INTO `users`
(`name`, `email`, `password`, `phone`, `address`, `role`)
VALUES
('Demo User', 'user@gmail.com',
 '$2y$12$5hFGdkA72pKCefXhKUxfUe4Y9s8aPVARVQOhzM0APjQYYylBbFkPO',
 '9876543210', 'Jodhpur, Rajasthan', 'user');

-- Categories
INSERT INTO `categories` (`name`) VALUES
('Indian'),
('Chinese'),
('South Indian'),
('Snacks'),
('Desserts');

-- Services
INSERT INTO `services` (`name`, `description`, `image`) VALUES
('Wedding Catering', 'Complete catering services for weddings, receptions and family celebrations.', 'events.jpg'),
('Corporate Catering', 'Professional food and catering arrangements for meetings and corporate events.', 'about1.jpg'),
('Birthday Catering', 'Fresh and delicious menus for birthday parties and private celebrations.', 'index.jpg'),
('Outdoor Catering', 'Flexible catering arrangements for outdoor functions and special occasions.', 'contact.jpg');

-- Products / menu
INSERT INTO `products`
(`name`, `description`, `price`, `stock`, `image`, `category_name`) VALUES
('Paneer Tikka', 'Soft paneer marinated with aromatic spices and grilled to perfection.', 220.00, 100, '1790148509_6ab37f9d84b9e.jpg', 'Indian'),
('Veg Biryani', 'Fragrant basmati rice cooked with vegetables and traditional spices.', 180.00, 100, '1790148557_6ab37fcd991e4.jpg', 'Indian'),
('Manchurian', 'Crispy vegetable balls tossed in a flavorful Indo-Chinese sauce.', 160.00, 100, '1790148584_6ab37fe83b4b7.jpg', 'Chinese'),
('Masala Dosa', 'Crispy dosa served with potato masala, sambar and chutney.', 140.00, 100, '1790148618_6ab3800ae2205.jpg', 'South Indian'),
('Samosa', 'Crispy pastry filled with spiced potatoes and peas.', 50.00, 150, '1790148668_6ab3803c6544d.jpg', 'Snacks'),
('Gulab Jamun', 'Soft traditional Indian dessert served with sweet syrup.', 90.00, 100, '1790148696_6ab3805864116.jpg', 'Desserts');

-- Gallery
INSERT INTO `gallery` (`image`, `title`) VALUES
('1790148726_6ab380762d721.jpg', 'Catering Event'),
('1790148763_6ab3809b8f5ad.jpg', 'Wedding Catering'),
('1790148785_6ab380b12c992.jpg', 'Food Presentation'),
('1790148837_6ab380e5dc150.jpg', 'Special Event'),
('1790148859_6ab380fbb1112.jpg', 'Delicious Menu');

-- Sample booking
INSERT INTO `bookings`
(`name`, `email`, `phone`, `event_type`, `event_date`, `guests`, `message`, `status`)
VALUES
('Demo Customer', 'customer@example.com', '9876543210', 'Wedding', '2027-01-15', 150,
 'Please arrange a vegetarian wedding catering menu.', 'pending');

-- Sample contact message
INSERT INTO `contact_messages`
(`name`, `email`, `phone`, `message`)
VALUES
('Demo Customer', 'customer@example.com', '9876543210',
 'I would like to know more about your catering packages.');

COMMIT;
