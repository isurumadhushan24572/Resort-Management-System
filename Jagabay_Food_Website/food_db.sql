-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2022 at 05:15 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `food_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `password`) VALUES
(1, 'admin', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `number` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` date NOT NULL DEFAULT current_timestamp(),
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `number` varchar(10) NOT NULL,
  `password` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

DELIMITER //

CREATE PROCEDURE update_user_password(
    IN p_user_id INT,
    IN p_old_pass VARCHAR(255),
    IN p_new_pass VARCHAR(255),
    IN p_confirm_pass VARCHAR(255),
    OUT p_message VARCHAR(255)
)
BEGIN
    DECLARE empty_pass VARCHAR(255);
    DECLARE prev_pass VARCHAR(255);
    DECLARE old_pass VARCHAR(255);

    SET empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';

    -- Get the previous password from the database
    SELECT password INTO prev_pass FROM users WHERE id = p_user_id;

    -- Check if old password matches
    SET old_pass = SHA1(p_old_pass);

    IF old_pass != empty_pass THEN
        IF old_pass != prev_pass THEN
            SET p_message = 'Old password not matched!';
        ELSE
            -- Check if new password and confirm password match
            IF p_new_pass != p_confirm_pass THEN
                SET p_message = 'Confirm password not matched!';
            ELSE
                -- Update the password
                IF p_new_pass != empty_pass THEN
                    UPDATE users
                    SET password = SHA1(p_new_pass)
                    WHERE id = p_user_id;

                    SET p_message = 'Password updated successfully!';
                ELSE
                    SET p_message = 'Please enter a new password!';
                END IF;
            END IF;
        END IF;
    ELSE
        SET p_message = 'Please enter the old password!';
    END IF;
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE update_user_address(
    IN p_user_id INT,
    IN p_flat VARCHAR(50),
    IN p_building VARCHAR(50),
    IN p_area VARCHAR(50),
    IN p_town VARCHAR(50),
    IN p_city VARCHAR(50),
    IN p_state VARCHAR(50),
    IN p_country VARCHAR(50),
    IN p_pin_code VARCHAR(6),
    OUT p_message VARCHAR(255)
)
BEGIN
    DECLARE rows_affected INT;

    -- Update the user's address
    UPDATE users
    SET flat = p_flat,
        building = p_building,
        area = p_area,
        town = p_town,
        city = p_city,
        state = p_state,
        country = p_country,
        pin_code = p_pin_code
    WHERE id = p_user_id;

    -- Check if the update was successful
    SELECT ROW_COUNT() INTO rows_affected;
    
    IF rows_affected > 0 THEN
        SET p_message = 'Address updated successfully!';
    ELSE
        SET p_message = 'Failed to update address. User ID not found or no changes made.';
    END IF;
END //

DELIMITER ;

-- Function to get total pending orders count
DELIMITER //

CREATE FUNCTION get_total_pending_orders()
RETURNS DECIMAL(10, 2)
BEGIN
    DECLARE total_pendings DECIMAL(10, 2);
    
    SELECT SUM(total_price)
    INTO total_pendings 
    FROM orders
    WHERE payment_status = 'pending';
    
    RETURN IFNULL(total_pendings, 0);
END //

DELIMITER ;
-- Function get_total_completed_orders
DELIMITER //

CREATE FUNCTION get_total_completed_orders()
RETURNS DECIMAL(10, 2)
BEGIN
    DECLARE total_completes DECIMAL(10, 2);
    
    SELECT SUM(total_price)
    INTO total_completes 
    FROM orders
    WHERE payment_status = 'completed';
    
    RETURN IFNULL(total_completes, 0);
END //

DELIMITER ;
-- view_total_completed_orders
CREATE VIEW view_total_completed_orders AS
SELECT count(*) AS total_completes
FROM orders
WHERE payment_status = 'completed';

-- view_pending_orders_count
CREATE VIEW view_pending_orders_count AS
SELECT COUNT(*) AS total_pending_orders
FROM orders
WHERE payment_status = 'pending';

DELIMITER //

-- trigger after_delete_all_cart_items
CREATE TRIGGER after_delete_all_cart_items
AFTER DELETE ON cart
FOR EACH ROW
BEGIN
    DELETE FROM cart
    WHERE user_id = OLD.user_id;
END //

DELIMITER ;
-- trigger after_update_cart_qty
DELIMITER //

CREATE TRIGGER after_update_cart_qty
AFTER UPDATE ON cart
FOR EACH ROW
BEGIN
    DECLARE item_price DECIMAL(10, 2);
    DECLARE new_quantity INT;
    DECLARE new_amount DECIMAL(10, 2);

    -- Get the price of the item being updated
    SELECT price INTO item_price FROM products WHERE id = NEW.product_id;

    -- Calculate new amount based on updated quantity
    SET new_quantity = NEW.quantity;
    SET new_amount = item_price * new_quantity;

    -- Update the amount and price in the cart table
    UPDATE cart
    SET amount = new_amount
    WHERE id = NEW.id;
END //

DELIMITER ;