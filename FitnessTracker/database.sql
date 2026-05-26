CREATE DATABASE IF NOT EXISTS fitnesstracker CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci;
USE fitnesstracker;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `height_cm` int(11) DEFAULT NULL,
  `weight_kg` float DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `subcategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `workouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,              
  `location` varchar(255) NOT NULL,           
  `category` int(11) DEFAULT NULL,            
  `subcategory` int(11) DEFAULT NULL,         
  `workout_date` date NOT NULL,               
  `duration_min` int(11) NOT NULL,            
  `calories_burned` int(11) DEFAULT NULL,     
  `notes` text DEFAULT NULL,
  `rpe` int(11) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `images` longtext DEFAULT NULL,
  `exercises` longtext DEFAULT NULL,
  `created_by` int(11) NOT NULL,              
  `updated_by` int(11) DEFAULT NULL,          
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`category`) REFERENCES `categories`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`subcategory`) REFERENCES `subcategories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workout_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`workout_id`) REFERENCES `workouts`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Silový trénink'),
(2, 'Kardio'),
(3, 'Jóga a protažení');

INSERT INTO `subcategories` (`id`, `category_id`, `name`) VALUES
(1, 1, 'Fullbody (Celé tělo)'),
(2, 1, 'Vrchní část (Prsa, Záda, Ruce)'),
(3, 1, 'Spodní část (Nohy, Zadek)'),
(4, 2, 'Běh'),
(5, 2, 'Cyklistika / Rotoped');