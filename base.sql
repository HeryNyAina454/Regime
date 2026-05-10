create database regime;
use regime;

-- Table des utilisateurs (Admin et Clients)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Profil utilisateur (pour le calcul du régime)
CREATE TABLE user_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    gender ENUM('H', 'F'),
    weight FLOAT,
    height FLOAT,
    age INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Table des régimes disponibles
CREATE TABLE regimes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    description TEXT,
    price DECIMAL(10,2),
    duration_days INT
);

ALTER TABLE user_profiles ADD COLUMN goal ENUM('gain', 'lose', 'ideal') DEFAULT NULL;
