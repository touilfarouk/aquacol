-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS `aquacole` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `aquacole`;

-- Create wilayas table
CREATE TABLE IF NOT EXISTS `wilayas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_ascii` varchar(255) NOT NULL,
  `longitude` decimal(10,6) DEFAULT NULL,
  `latitude` decimal(10,6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create communes table
CREATE TABLE IF NOT EXISTS `communes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wilaya_id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_ascii` varchar(255) NOT NULL,
  `longitude` decimal(10,6) DEFAULT NULL,
  `latitude` decimal(10,6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wilaya_id` (`wilaya_id`),
  CONSTRAINT `communes_ibfk_1` FOREIGN KEY (`wilaya_id`) REFERENCES `wilayas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create coordinates table (main concessions table)
CREATE TABLE IF NOT EXISTS `coordinates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_concession` varchar(50) NOT NULL,
  `nom_zone` varchar(255) NOT NULL,
  `wilaya_id` int(11) DEFAULT NULL,
  `commune_id` int(11) DEFAULT NULL,
  `coordonnee_a` varchar(100) NOT NULL,
  `coordonnee_b` varchar(100) NOT NULL,
  `coordonnee_c` varchar(100) NOT NULL,
  `coordonnee_d` varchar(100) NOT NULL,
  `format_coordonnees` enum('decimal','dms','utm') DEFAULT 'decimal',
  `superficie` decimal(10,2) DEFAULT NULL,
  `distance_voi_acces` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `visible` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_concession` (`code_concession`),
  KEY `wilaya_id` (`wilaya_id`),
  KEY `commune_id` (`commune_id`),
  CONSTRAINT `coordinates_ibfk_1` FOREIGN KEY (`wilaya_id`) REFERENCES `wilayas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `coordinates_ibfk_2` FOREIGN KEY (`commune_id`) REFERENCES `communes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
