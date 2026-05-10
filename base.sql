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


-- Table activités sportives
CREATE TABLE sport_activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    duration_minutes INT,
    frequency_per_week INT,
    goal ENUM('gain', 'lose', 'ideal') NOT NULL
);

-- Compléter la table regimes
ALTER TABLE regimes
    ADD COLUMN goal        ENUM('gain', 'lose', 'ideal') NOT NULL AFTER duration_days,
    ADD COLUMN meat_pct    INT DEFAULT 0 AFTER goal,
    ADD COLUMN fish_pct    INT DEFAULT 0 AFTER meat_pct,
    ADD COLUMN poultry_pct INT DEFAULT 0 AFTER fish_pct,
    ADD COLUMN weight_change_kg FLOAT DEFAULT 0 AFTER poultry_pct;

-- Table portefeuille utilisateur
CREATE TABLE wallets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE,
    balance DECIMAL(10,2) DEFAULT 0.00,
    is_gold BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Table commandes
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    regime_id INT,
    price_paid DECIMAL(10,2),
    ordered_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (regime_id) REFERENCES regimes(id)
);

-- ── Données : 5 régimes ──────────────────────────────────────
INSERT INTO regimes (name, description, price, duration_days, goal, meat_pct, fish_pct, poultry_pct, weight_change_kg) VALUES
('Régime Prise de Masse',    'Alimentation riche en protéines pour favoriser la prise de masse musculaire et pondérale.',         29.99,  30, 'gain',  40, 20, 40,  4.0),
('Régime Hyperprotéiné',     'Apport élevé en protéines animales pour soutenir la croissance musculaire progressive.',             39.99,  60, 'gain',  35, 15, 50,  6.5),
('Régime Minceur Équilibré', 'Programme équilibré faible en calories pour une perte de poids progressive et durable.',            24.99,  30, 'lose', -3.0, 30, 40, 30),
('Régime Détox Slim',        'Réduction des graisses avec apport élevé en poisson et volaille maigre.',                           34.99,  45, 'lose',  10, 50, 40, -5.0),
('Régime IMC Idéal',         'Programme complet pour atteindre et maintenir un IMC entre 18.5 et 25 sur le long terme.',          44.99,  90, 'ideal', 25, 35, 40,  0.0);

-- Corriger l'ordre des colonnes pour la ligne lose (réinsérer proprement)
UPDATE regimes SET weight_change_kg = -3.0 WHERE name = 'Régime Minceur Équilibré';
UPDATE regimes SET weight_change_kg = -5.0 WHERE name = 'Régime Détox Slim';

-- ── Données : 5 activités sportives ─────────────────────────
INSERT INTO sport_activities (name, description, duration_minutes, frequency_per_week, goal) VALUES
('Musculation',       'Séances de musculation avec charges pour développer la masse musculaire.',          60, 4, 'gain'),
('Yoga doux',         'Séances de yoga pour tonifier le corps sans perdre de poids excessivement.',        45, 3, 'gain'),
('Cardio intensif',   'Course, vélo ou natation à haute intensité pour brûler les calories rapidement.',  45, 5, 'lose'),
('Circuit training',  'Enchaînement d\'exercices variés pour maximiser la dépense calorique.',             40, 4, 'lose'),
('Marche active',     'Marche rapide quotidienne combinée à des étirements pour maintenir l\'équilibre.',  35, 5, 'ideal');

-- Initialiser le portefeuille pour les utilisateurs existants
INSERT INTO wallets (user_id, balance, is_gold)
SELECT id, 0.00, FALSE FROM users WHERE is_admin = FALSE;

-- UPDATE wallets
-- SET balance = 200.00
-- WHERE user_id = 1;