-- =========================================================
--  RégimePro — Script de base de données
--  Encodage : utf8mb4 | Monnaie : Euro (€)
-- =========================================================

CREATE DATABASE IF NOT EXISTS regime
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE regime;

-- =========================================================
--  TABLES
-- =========================================================

CREATE TABLE users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(100)  NOT NULL,
    email      VARCHAR(150)  NOT NULL UNIQUE,
    password   VARCHAR(255)  NOT NULL,
    is_admin   BOOLEAN       DEFAULT FALSE,
    created_at DATETIME      DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_profiles (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    gender  ENUM('H', 'F'),
    weight  FLOAT,
    height  FLOAT,
    age     INT,
    goal    ENUM('gain', 'lose', 'ideal') DEFAULT NULL,

    CONSTRAINT fk_user_profiles_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);

CREATE TABLE regimes (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    name             VARCHAR(100)   NOT NULL,
    description      TEXT,
    price            DECIMAL(10,2)  NOT NULL,
    duration_days    INT            NOT NULL,
    goal             ENUM('gain', 'lose', 'ideal') NOT NULL,
    meat_pct         INT  DEFAULT 0,
    fish_pct         INT  DEFAULT 0,
    poultry_pct      INT  DEFAULT 0,
    weight_change_kg FLOAT DEFAULT 0
);

CREATE TABLE sport_activities (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    name                VARCHAR(100) NOT NULL,
    description         TEXT,
    duration_minutes    INT,
    frequency_per_week  INT,
    goal                ENUM('gain', 'lose', 'ideal') NOT NULL
);

CREATE TABLE wallets (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    balance DECIMAL(10,2) DEFAULT 0.00,
    is_gold BOOLEAN       DEFAULT FALSE,

    CONSTRAINT fk_wallets_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);

CREATE TABLE orders (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT            NOT NULL,
    regime_id  INT            NOT NULL,
    price_paid DECIMAL(10,2)  NOT NULL,
    ordered_at DATETIME       DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_orders_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_orders_regime
        FOREIGN KEY (regime_id) REFERENCES regimes(id)
        ON DELETE CASCADE
);

CREATE TABLE wallet_codes (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    code       VARCHAR(50)    UNIQUE NOT NULL,
    amount     DECIMAL(10,2)  NOT NULL,
    is_used    BOOLEAN        DEFAULT FALSE,
    used_by    INT            DEFAULT NULL,
    used_at    DATETIME       DEFAULT NULL,
    created_at DATETIME       DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (used_by) REFERENCES users(id)
);

CREATE TABLE regime_pricing (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    regime_id     INT           NOT NULL,
    duration_days INT           NOT NULL,
    price         DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (regime_id) REFERENCES regimes(id)
    ON DELETE CASCADE
);

CREATE TABLE settings (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    key_name    VARCHAR(100)  UNIQUE NOT NULL,
    value       VARCHAR(255)  NOT NULL,
    label       VARCHAR(150),
    description TEXT
);

-- =========================================================
--  DONNÉES : RÉGIMES  (prix en Euro €)
-- =========================================================

INSERT INTO regimes (name, description, price, duration_days, goal, meat_pct, fish_pct, poultry_pct, weight_change_kg) VALUES
(
    'Fomba Fihinana Fitomboan-danja',
    'Sakafo manan-karena proteinina mba hanampy ny fitomboana ny hery ara-tsaina sy ara-batana.',
    29.99, 30, 'gain', 40, 20, 40,  4.0
),
(
    'Fomba Fihinana Proteinina Ambony',
    'Fidiram-be amin\'ny proteinina avy amin\'ny biby mba hanohana ny fitomboana ny hery ara-tsaina.',
    39.99, 60, 'gain', 35, 15, 50,  6.5
),
(
    'Fomba Fihinana Mitory Lanja',
    'Fandaharam-pisakafoanana mirindra maivana kaloria mba hahafahan\'ny lanja mihena tsara.',
    24.99, 30, 'lose', 30, 40, 30, -3.0
),
(
    'Fomba Fihinana Fanadiovana',
    'Fampihenana ny tavy miaraka amin\'ny trondro sy akoho masaka mba hahafahan\'ny lanja mihena haingana.',
    34.99, 45, 'lose', 10, 50, 40, -5.0
),
(
    'Fomba Fihinana IMC Mety',
    'Fandaharam-pisakafoanana feno mba hahatratra sy mitazona IMC eo anelanelan\'ny 18.5 sy 25.',
    44.99, 90, 'ideal', 25, 35, 40,  0.0
);

-- =========================================================
--  DONNÉES : ACTIVITÉS SPORTIVES
-- =========================================================

INSERT INTO sport_activities (name, description, duration_minutes, frequency_per_week, goal) VALUES
(
    'Fampiofanana Vatan-tenantsika',
    'Fampiofanana miaraka amin\'ny mavesatra mba hampitomboana ny hery ara-tsaina.',
    60, 4, 'gain'
),
(
    'Yoga Malemilemy',
    'Fampiofanana yoga mba hanome endrika ny vatana nefa tsy hahafatesana lanja be loatra.',
    45, 3, 'gain'
),
(
    'Cardio Mafy',
    'Mihazakazaka, misatroka baisikileta na milomano amin\'ny hery mba handoroana kaloria haingana.',
    45, 5, 'lose'
),
(
    'Fampiofanana Circuit',
    'Fandaharam-pianarana isan\'karazany mba hanakamaroan\'ny fandoroana kaloria.',
    40, 4, 'lose'
),
(
    'Fandehanana Malefaka',
    'Fandehanana haingana andavanandro miaraka amin ny fitaizana mba hitazona ny fifandanjana.',
    35, 5, 'ideal'
);

-- =========================================================
--  DONNÉES : UTILISATEUR ADMIN
-- =========================================================

INSERT INTO users (username, email, password, is_admin) VALUES
('admin', 'admin@gmail.com', '$2y$12$nl1Rsk383/06g3ssSfZ1OucUhoUV8JPFPSm7FAloVh2cI2LGaG9ES', TRUE);

-- =========================================================
--  DONNÉES : UTILISATEURS  (id 2 → 6)
-- =========================================================

INSERT INTO users (username, email, password, is_admin) VALUES
('Rakoto',      'rakoto@gmail.com',      '$2y$12$rmNt/OtB0RjiYDk52sdOSuVr3OQUsJb380q0TkEZub7HDDn5G7mCq',      FALSE),
('Rasoa',       'rasoa@gmail.com',       '$2y$12$agIa.WLTi93Wu1KVZpbE0uH3N3DyRWbZ/BT06SEqEH4vK4Fvx3Tx.',       FALSE),
('Andry',       'andry@gmail.com',       '$2y$12$pYh8hmkO/4TAPSZCscGuvOkbQAJkebrkCOifP9I8y/LSB9cZ7sgDC',       FALSE),
('Miora',       'miora@gmail.com',       '$2y$12$NAY5QCAKkLtye/NJx6xFrO7ZvN7VfkpCfZ81z3t8.IqSLwpdUMPQi',       FALSE),
('Herimanana',  'herimanana@gmail.com',  '$2y$12$YtS1GR8C7y/aw6ITSLBjL./QPQVa8sjvsBmeJvueIgtouXIPFszAK',  FALSE);

-- =========================================================
--  DONNÉES : PROFILS UTILISATEURS
-- =========================================================
--  id 2 = Rakoto     | H | 23 ans | 58 kg | 1.70 m | gain
--  id 3 = Rasoa      | F | 29 ans | 72 kg | 1.62 m | lose
--  id 4 = Andry      | H | 35 ans | 90 kg | 1.75 m | lose
--  id 5 = Miora      | F | 22 ans | 52 kg | 1.60 m | ideal
--  id 6 = Herimanana | H | 41 ans | 68 kg | 1.68 m | ideal

INSERT INTO user_profiles (user_id, gender, weight, height, age, goal) VALUES
(2, 'H', 58.0, 170.0, 23, 'gain'),
(3, 'F', 72.0, 162.0, 29, 'lose'),
(4, 'H', 90.0, 175.0, 35, 'lose'),
(5, 'F', 52.0, 160.0, 22, 'ideal'),
(6, 'H', 68.0, 168.0, 41, 'ideal');

-- =========================================================
--  DONNÉES : PORTEFEUILLES UTILISATEURS
-- =========================================================

INSERT INTO wallets (user_id, balance, is_gold) VALUES
(2,   5.00, FALSE),
(3,  50.00, TRUE),
(4,  10.00, FALSE),
(5,  75.00, TRUE),
(6,  15.00, FALSE);

-- =========================================================
--  DONNÉES : CODES PORTEFEUILLE  (montants en Euro €)
-- =========================================================

INSERT INTO wallet_codes (code, amount) VALUES
('REGIME-ATOMBOHA-5',    5.00),
('REGIME-BOOST-10',     10.00),
('REGIME-GOLD-25',      25.00),
('FAHASALAMANA-2024-15',15.00),
('FAHASALAMANA-2024-25',25.00),
('MIHAZAKAZAKA-30',     30.00),
('MIHAZAKAZAKA-5',       5.00),
('TONGASOA-20',         20.00),
('MAHIA-10',            10.00),
('MAHIA-40',            40.00),
('FANATANJAHANA-15',    15.00),
('FANATANJAHANA-35',    35.00),
('VIP-FIDIRANA-100',   100.00),
('PROMO-FAHAVARATRA-20',20.00),
('PROMO-FAHAVARATRA-50',50.00);

-- =========================================================
--  DONNÉES : TARIFICATION PAR DURÉE  (prix en Euro €)
-- =========================================================

INSERT INTO regime_pricing (regime_id, duration_days, price) VALUES
(1, 30,  29.99), (1, 60,  49.99), (1, 90,  69.99),
(2, 30,  39.99), (2, 60,  64.99), (2, 90,  89.99),
(3, 30,  24.99), (3, 60,  39.99), (3, 90,  54.99),
(4, 30,  34.99), (4, 60,  54.99), (4, 90,  74.99),
(5, 30,  44.99), (5, 60,  74.99), (5, 90, 104.99);

-- =========================================================
--  DONNÉES : PARAMÈTRES  (valeurs en Euro €)
-- =========================================================

INSERT INTO settings (key_name, value, label, description) VALUES
('gold_price',    '99.99', 'Prix Option Gold (€)',          'Montant unique pour activer l\'option Gold'),
('gold_discount', '15',    'Remise Gold (%)',               'Pourcentage de remise appliqué aux membres Gold'),
('max_recharge',  '500',   'Montant max recharge (€)',      'Montant maximum qu\'un utilisateur peut recharger par code');
