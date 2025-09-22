-- Create database
CREATE DATABASE IF NOT EXISTS aquacole CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE aquacole;

-- Simple coordinates table
CREATE TABLE IF NOT EXISTS coordinates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_zone VARCHAR(255),
    wilaya_name_ascii VARCHAR(255),
    commune_name_ascii VARCHAR(255),
        code_wilaya VARCHAR(5),
    code_commune VARCHAR(5),
    visible TINYINT(1) DEFAULT 1,
    distance_voi_acces INT,
    code_concession VARCHAR(255), -- code_wilaya + code_commune + code_concession = unique
    superficie DECIMAL(10,2),
    description TEXT,
    coordonnee_a VARCHAR(100) NOT NULL,
    coordonnee_b VARCHAR(100) NOT NULL,
    coordonnee_c VARCHAR(100) NOT NULL,
    coordonnee_d VARCHAR(100) NOT NULL,
    format_coordonnees VARCHAR(20) DEFAULT 'decimal',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
