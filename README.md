# Gestion_Bibliotheque-
DÉVELOPPEMENT D’UNE APPLICATION WEB DE  GESTION DE BIBLIOTHÈQUE
# 📚 Système de Gestion de Bibliothèque - HETEC

Ce projet est une application web de gestion de bibliothèque développée dans le cadre du cours de Génie Logiciel (L2). Elle permet de gérer les membres, les livres et les emprunts de manière centralisée.

## 🚀 Fonctionnalités
- **Gestion des Utilisateurs** : Liste des membres, attribution des rôles (Admin/Étudiant), suppression.
- **Gestion des Livres** : Ajout, modification et suivi du stock.
- **Système d'Emprunts** : Suivi en temps réel des livres détenus par chaque étudiant.
- **Interface Admin** : Tableau de bord sécurisé avec statistiques.

🗄️ Structure de la Base de Données
Le projet s'appuie sur une base de données relationnelle MySQL structurée autour de trois entités principales :
1. Table users (Membres)
Stocke les informations des étudiants et des administrateurs.
id : Clé primaire (Auto-increment)
matricule : Identifiant unique HETEC
nom : Nom complet
email : Adresse de contact
role : Type d'utilisateur (ETUDIANT, ADMIN)
date_inscription : Date de création du compte
2. Table livres (Catalogue)
Contient l'inventaire de la bibliothèque.
id : Clé primaire
titre : Nom de l'ouvrage
auteur : Écrivain
quantite : Nombre d'exemplaires disponibles
3. Table emprunts (Table de Jointure)
Relie les utilisateurs aux livres qu'ils détiennent.
id : Clé primaire
user_id : Clé étrangère vers users(id)
livre_id : Clé étrangère vers livres(id)
date_emprunt : Date de sortie du livre

## 🛠️ Technologies Utilisées
- **Backend** : PHP 8.x (Architecture PDO pour la sécurité)
- **Base de données** : MySQL / MariaDB
- **Frontend** : HTML5, CSS3 (Bootstrap 5), JavaScript
- **Versionnage** : Git & GitHub

## 📦 Installation
1. Cloner le projet : `git clone https://github.com/votre-nom/Gestion_Bibliotheque.git`
2. Importer la base de données `bibliotheque.sql` dans PHPMyAdmin.
3. Configurer les accès dans le fichier `db.php`.
4. Lancer via XAMPP (Apache).

## 👥 Équipe (trinome)
-TRAOREeloppement Backend (Gestion Membres & SQL).
- **[Nom de ton Binôme]** : Développement Frontend & CRUD Livres.
-