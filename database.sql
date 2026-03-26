-- 1. Création de la base de données
CREATE DATABASE IF NOT EXISTS g_bibliotheque CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE g_bibliotheque;

-- 2. Table des Utilisateurs (Admins et Étudiants)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricule VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    nom VARCHAR(100) NOT NULL,
    ecole VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('etudiant', 'admin') DEFAULT 'etudiant',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Table des Livres
CREATE TABLE IF NOT EXISTS livres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    auteur VARCHAR(100) NOT NULL,
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 4. Table des Emprunts (Lien entre Users et Livres)
CREATE TABLE IF NOT EXISTS emprunts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    livre_id INT NOT NULL,
    date_emprunt DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_retour DATETIME DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (livre_id) REFERENCES livres(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5. Insertion de l'ADMINISTRATEUR par défaut
-- Identifiants : Matricule = ADMIN001 / Password = admin123
INSERT INTO users (matricule, email, nom, ecole, password, role) 
VALUES (
    'ADMIN001', 
    'admin@hetec.bf', 
    'Super Admin', 
    'HETEC', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- Mot de passe : password
    'admin'
);

-- 6. Insertion de quelques livres de test
INSERT INTO livres (titre, auteur, stock) VALUES 
('Algorithmique Avancée', 'Thomas Cormen', 5),
('PHP & MySQL pour les Nuls', 'Janet Valade', 3),
('Génie Logiciel L2', 'Sommerville', 2);

ALTER TABLE emprunts 
ADD COLUMN date_limite DATE AFTER date_emprunt;


-- Met une date limite à aujourd'hui pour tous les emprunts
UPDATE emprunts SET date_limite = CURDATE();

-- Ou met une date dépassée pour tester le compteur de retards
UPDATE emprunts SET date_limite = '2026-01-01' WHERE id = 1;

SELECT u.id, u.nom, u.matricule, u.email, u.user_role, u.date_inscription, GROUP_CONCAT(l.titre SEPARATOR ', ') as livres_empruntes
FROM users u
LEFT JOIN emprunts e ON u.id = e.user_id
LEFT JOIN livres l ON e.livre_id = l.id
GROUP BY u.id

-- 1. On crée la colonne
ALTER TABLE users ADD COLUMN date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- 2. On remplit les dates pour les anciens membres (pour ne pas avoir de vide)
UPDATE users SET date_inscription = NOW() WHERE date_inscription IS NULL;

UPDATE users SET date_inscription = NOW() WHERE date_inscription IS NULL;

UPDATE users SET date_inscription = NOW()

SELECT u.*, GROUP_CONCAT(l.titre SEPARATOR ', ') as livres_empruntes
FROM users u
LEFT JOIN emprunts e ON u.id = e.user_id AND e.date_retour IS NULL -- Filtre ici
LEFT JOIN livres l ON e.livre_id = l.id
GROUP BY u.id