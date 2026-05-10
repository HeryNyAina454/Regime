CREATE DATABASE regime
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE regime;

-- =========================================================
-- TABLE DES UTILISATEURS
-- =========================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================
-- PROFIL UTILISATEUR
-- =========================================================
CREATE TABLE user_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    gender ENUM('H', 'F'),
    weight FLOAT,
    height FLOAT,
    age INT,
    goal ENUM('gain', 'lose', 'ideal') DEFAULT NULL,

    CONSTRAINT fk_user_profiles_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);

-- =========================================================
-- TABLE DES RÉGIMES
-- =========================================================
CREATE TABLE regimes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    duration_days INT NOT NULL,

    goal ENUM('gain', 'lose', 'ideal') NOT NULL,

    meat_pct INT DEFAULT 0,
    fish_pct INT DEFAULT 0,
    poultry_pct INT DEFAULT 0,

    weight_change_kg FLOAT DEFAULT 0
);

-- =========================================================
-- ACTIVITÉS SPORTIVES
-- =========================================================
CREATE TABLE sport_activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    duration_minutes INT,
    frequency_per_week INT,
    goal ENUM('gain', 'lose', 'ideal') NOT NULL
);

-- =========================================================
-- PORTEFEUILLE UTILISATEUR
-- =========================================================
CREATE TABLE wallets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    balance DECIMAL(10,2) DEFAULT 0.00,
    is_gold BOOLEAN DEFAULT FALSE,

    CONSTRAINT fk_wallets_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);

-- =========================================================
-- COMMANDES
-- =========================================================
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    regime_id INT NOT NULL,
    price_paid DECIMAL(10,2) NOT NULL,
    ordered_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_orders_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_orders_regime
        FOREIGN KEY (regime_id)
        REFERENCES regimes(id)
        ON DELETE CASCADE
);

-- =========================================================
-- DONNÉES : RÉGIMES
-- =========================================================
INSERT INTO regimes (
    name,
    description,
    price,
    duration_days,
    goal,
    meat_pct,
    fish_pct,
    poultry_pct,
    weight_change_kg
) VALUES
(
    'Régime Prise de Masse',
    'Alimentation riche en protéines pour favoriser la prise de masse musculaire et pondérale.',
    29.99,
    30,
    'gain',
    40,
    20,
    40,
    4.0
),
(
    'Régime Hyperprotéiné',
    'Apport élevé en protéines animales pour soutenir la croissance musculaire progressive.',
    39.99,
    60,
    'gain',
    35,
    15,
    50,
    6.5
),
(
    'Régime Minceur Équilibré',
    'Programme équilibré faible en calories pour une perte de poids progressive et durable.',
    24.99,
    30,
    'lose',
    30,
    40,
    30,
    -3.0
),
(
    'Régime Détox Slim',
    'Réduction des graisses avec apport élevé en poisson et volaille maigre.',
    34.99,
    45,
    'lose',
    10,
    50,
    40,
    -5.0
),
(
    'Régime IMC Idéal',
    'Programme complet pour atteindre et maintenir un IMC entre 18.5 et 25 sur le long terme.',
    44.99,
    90,
    'ideal',
    25,
    35,
    40,
    0.0
);

-- =========================================================
-- DONNÉES : ACTIVITÉS SPORTIVES
-- =========================================================
INSERT INTO sport_activities (
    name,
    description,
    duration_minutes,
    frequency_per_week,
    goal
) VALUES
(
    'Musculation',
    'Séances de musculation avec charges pour développer la masse musculaire.',
    60,
    4,
    'gain'
),
(
    'Yoga doux',
    'Séances de yoga pour tonifier le corps sans perdre de poids excessivement.',
    45,
    3,
    'gain'
),
(
    'Cardio intensif',
    'Course, vélo ou natation à haute intensité pour brûler les calories rapidement.',
    45,
    5,
    'lose'
),
(
    'Circuit training',
    'Enchaînement d''exercices variés pour maximiser la dépense calorique.',
    40,
    4,
    'lose'
),
(
    'Marche active',
    'Marche rapide quotidienne combinée à des étirements pour maintenir l''équilibre.',
    35,
    5,
    'ideal'
);

-- UPDATE wallets
-- SET balance = 200.00
-- WHERE user_id = 1;