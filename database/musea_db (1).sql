-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2025 at 05:37 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `musea_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `artworks`
--

CREATE TABLE `artworks` (
  `id` int(10) UNSIGNED NOT NULL,
  `artist_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `category` enum('Painting','Canvas','Drawing','Sculpture','Vase','Basket','Other') DEFAULT 'Other',
  `price` decimal(10,2) NOT NULL,
  `stock` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `status` enum('active','archived','pending','declined') NOT NULL DEFAULT 'pending',
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artworks`
--

INSERT INTO `artworks` (`id`, `artist_id`, `title`, `description`, `category`, `price`, `stock`, `status`, `image_url`, `created_at`) VALUES
(1, 1, 'The test of things', 'non binary gender fluidization condensation/evaporation', 'Canvas', 999.00, 3, 'active', '1762175493_RobloxScreenShot20250917_222349522.png', '2025-11-02 07:21:30'),
(2, 5, 'Canvas Print — \'Sunrise\'', 'A study of morning light and texture — created to explore the subtle transition of color at dawn and captured on archival canvas to retain vibrancy.', 'Canvas', 10499.00, 1, 'active', '1762168954_marklloyd.png.jpg', '2025-11-03 11:22:34'),
(3, 16, 'Woodland Path with Trees', 'woodland path with trees and everything nice', 'Painting', 1250.00, 1, 'active', '1762252586_birmingham-museums-trust-zWE5pOLWkio-unsplash.jpg', '2025-11-04 10:36:26'),
(4, 17, 'Girl in White Picking Flowers', 'girl in the flowers', 'Painting', 3500.00, 1, 'active', '1762252871_europeana-VsnDYMWollM-unsplash.jpg', '2025-11-04 10:41:11'),
(5, 18, 'Still Life with Flowers and Blue Ribbon', 'ang sarap tignan', 'Painting', 2500.00, 1, 'active', '1762253079_europeana-YIfFVwDcgu8-unsplash.jpg', '2025-11-04 10:44:39'),
(6, 19, 'Baroque Ceiling with Angels', 'andaming person omg!', 'Painting', 1500.00, 1, 'active', '1762253356_adrianna-geo-1rBg5YSi00c-unsplash.jpg', '2025-11-04 10:49:16'),
(7, 20, 'Castle on Rocky', 'fantasy rocky castle', 'Painting', 2250.00, 1, 'active', '1762253528_birmingham-museums-trust-sJr8LDyEf7k-unsplash.jpg', '2025-11-04 10:52:08'),
(8, 12, 'Bird Studies on Kraft Paper', 'birds of the same father', 'Drawing', 2000.00, 1, 'active', '1762254863_averylane.jpg', '2025-11-04 11:14:23'),
(9, 13, 'Woman Resting on Bed, Ink', 'beauty rest', 'Drawing', 3000.00, 1, 'active', '1762255168_woman resting.png', '2025-11-04 11:19:28'),
(10, 14, 'Vase of Mixed Flowers on Black', 'super vase', 'Drawing', 3750.00, 1, 'active', '1762255326_vaseofmixed.png', '2025-11-04 11:22:06'),
(11, 15, 'Botanical Bouquet with Lilies and Roses', 'bouquet of lilies and roses', 'Drawing', 2750.00, 1, 'active', '1762255518_botanicalbouquet.png', '2025-11-04 11:25:18'),
(12, 11, 'Crowd of Cartoon Faces on Pink', 'cartoon', 'Drawing', 4000.00, 1, 'active', '1762255806_crowdofcartoon.png', '2025-11-04 11:30:06'),
(13, 9, 'Blue Brushstroke Texture', 'brushstroke blue', 'Canvas', 7000.00, 1, 'active', '1762256526_bluebrushstroke.png', '2025-11-04 11:42:06'),
(14, 10, 'Tree Branches with Colorful Leaves', 'colorful nature', 'Canvas', 4750.00, 1, 'active', '1762256665_treebranches.png', '2025-11-04 11:44:25'),
(15, 6, 'Red Buds on Blue Floral', 'red buds on blue floral', 'Canvas', 4200.00, 1, 'active', '1762256803_redbuds.png', '2025-11-04 11:46:43'),
(16, 7, 'Blue Diagonals with Black Drips', 'diagonal blue', 'Canvas', 6000.00, 1, 'active', '1762256975_bluediagonal.png', '2025-11-04 11:49:35'),
(17, 8, 'Dark Rainbow Swirl Abstract', 'Rainbow after the rain', 'Canvas', 3000.00, 1, 'active', '1762257117_darkrainbow.png', '2025-11-04 11:51:57'),
(18, 17, 'Painted Vase', 'Blue Panited Vase', 'Vase', 4200.00, 1, 'active', '1762257403_painted-vase.png', '2025-11-04 11:56:43'),
(19, 5, 'Wooden Sculpture', 'Sculpture', 'Sculpture', 7000.00, 1, 'active', '1762257602_wooden-sculpture.png', '2025-11-04 12:00:02'),
(20, 11, 'Handwoven Basket', 'Basket', 'Basket', 4750.00, 1, 'active', '1762257800_hand-woven-basket.png', '2025-11-04 12:03:20');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `created_at`) VALUES
(1, 1, '2025-11-05 18:12:45');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `cart_id` int(10) UNSIGNED NOT NULL,
  `artwork_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `price_each` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `artwork_id`, `quantity`, `price_each`) VALUES
(14, 1, 18, 2, 4200.00),
(15, 1, 17, 2, 3000.00);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `subject` varchar(200) NOT NULL,
  `reason` enum('general','order','wholesale','press','feedback') NOT NULL,
  `order_number` varchar(50) DEFAULT NULL,
  `preferred_contact` enum('email','phone') DEFAULT 'email',
  `message` text NOT NULL,
  `consent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `order_number` varchar(30) NOT NULL,
  `status` enum('pending','paid','shipped','completed','cancelled') DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `artwork_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `price_each` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` enum('card','paypal','bank_transfer','cod') NOT NULL,
  `status` enum('pending','succeeded','failed','refunded') DEFAULT 'pending',
  `transaction_ref` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `artwork_id` int(10) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact_number` varchar(50) DEFAULT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password_hash`, `address`, `contact_number`, `avatar_url`, `created_at`) VALUES
(1, 'Ino', 'Yasha', 'inuyasha@gmail.com', '$2y$10$ejkMZFrkF8QBmm7iooQheeKNux8PKlUwpTwW6jDY5U5V8u8ePe4V6', '123123123 kapampangan street', '09856834737', 'uploads/avatars/user_1_1762365067_557120152_2065522080944599_4936648027586025941_n.jpg', '2025-10-26 05:17:28'),
(2, 'Christine Joy', 'Almajar', 'cj@gmail.com', '$2y$10$bs.BZgnDiDrnq441beuSYeggvkOT5TXZU9JruHluKIRCPgXKvlvXC', 'taga san roque ako123 street', '09125358473', NULL, '2025-10-27 14:07:27'),
(5, 'Mark', 'Lloyd', 'mark.lloyd@musea.art', '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'San antonio Bayanihan street 143', '09163873774', 'uploads/avatars/mark_lloyd.png', '2025-11-03 10:10:58'),
(6, 'Ken', 'Tan', 'ken.tan@musea.art', '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'san roque', '097317483874', 'uploads/avatars/ken_tan.png', '2025-11-03 10:10:58'),
(7, 'Maya', 'Ortiz', 'maya.ortiz@musea.art', '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'Quezon', '09382756174', 'uploads/avatars/maya_ortiz.png', '2025-11-03 10:10:58'),
(8, 'Liam', 'Becker', 'liam.becker@musea.art', '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'Makati', '09237858749', 'uploads/avatars/liam_becker.png', '2025-11-03 10:10:58'),
(9, 'Naomi', 'Fields', 'naomi.fields@musea.art', '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'calamba', '09232084702', 'uploads/avatars/naomi_fields.png', '2025-11-03 10:10:58'),
(10, 'Aria', 'Chen', 'aria.chen@musea.art', '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'Batangas', '09271298375', 'uploads/avatars/aria_chen.png', '2025-11-03 10:10:58'),
(11, 'Riley', 'Park', 'riley.park@musea.art', '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'tondo', '09284639470', 'uploads/avatars/riley_park.png', '2025-11-03 10:10:58'),
(12, 'Avery', 'Lane', 'avery.lane@musea.art', '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'manila', '09786431234', 'uploads/avatars/avery_lane.png', '2025-11-03 10:10:58'),
(13, 'Quinn', 'Harper', 'quinn.harper@musea.art', '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'cabuyao', '09451238765', 'uploads/avatars/quinn_harper.png', '2025-11-03 10:10:58'),
(14, 'Noah', 'Voss', 'noah.voss@musea.art', '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'makati', '09752387549', 'uploads/avatars/noah_voss.png', '2025-11-03 10:10:58'),
(15, 'Iris', 'Bennett', 'iris.bennett@musea.art', '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'Baguio', '09284761937', 'uploads/avatars/iris_bennett.png', '2025-11-03 10:10:58'),
(16, 'Clara', 'Benton', 'clara.benton@musea.art', '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', '123 street', '09385724', 'uploads/avatars/clara_benton.png', '2025-11-03 10:10:58'),
(17, 'Sophie', 'Hart', 'sophie.hart@musea.art', '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'Sta. Rosa', '0953855427', 'uploads/avatars/sophie_hart.png', '2025-11-03 10:10:58'),
(18, 'Jonas', 'Reed', 'jonas.reed@musea.art', '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', NULL, NULL, 'uploads/avatars/jonas_reed.png', '2025-11-03 10:10:58'),
(19, 'Lucia', 'Moretti', 'lucia.moretti@musea.art', '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'Dyan sa kanto', '09871236543', 'uploads/avatars/lucia_moretti.png', '2025-11-03 10:10:58'),
(20, 'Graham', 'Wells', 'graham.wells@musea.art', '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'philip street', '09874561376', 'uploads/avatars/graham_wells.png', '2025-11-03 10:10:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artworks`
--
ALTER TABLE `artworks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `fk_artworks_artist` (`artist_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cart_items_cart` (`cart_id`),
  ADD KEY `fk_cart_items_artwork` (`artwork_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_orders_user` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_items_order` (`order_id`),
  ADD KEY `fk_order_items_artwork` (`artwork_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_payments_order` (`order_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reviews_artwork` (`artwork_id`),
  ADD KEY `fk_reviews_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artworks`
--
ALTER TABLE `artworks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `artworks`
--
ALTER TABLE `artworks`
  ADD CONSTRAINT `fk_artworks_artist` FOREIGN KEY (`artist_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_cart_items_artwork` FOREIGN KEY (`artwork_id`) REFERENCES `artworks` (`id`),
  ADD CONSTRAINT `fk_cart_items_cart` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_artwork` FOREIGN KEY (`artwork_id`) REFERENCES `artworks` (`id`),
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_artwork` FOREIGN KEY (`artwork_id`) REFERENCES `artworks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
