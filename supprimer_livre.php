<?php
session_start();
require_once 'db.php';

// Sécurité : Seul l'admin peut supprimer
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    try {
        // Optionnel : On vérifie si le livre est actuellement emprunté avant de supprimer
        $check = $pdo->prepare("SELECT COUNT(*) FROM emprunts WHERE livre_id = ? AND date_retour IS NULL");
        $check->execute([$id]);
        
        if ($check->fetchColumn() > 0) {
            // Si le livre est dehors, on ne supprime pas pour éviter de casser l'historique
            header("Location: liste_livres.php?error=en_cours_d_emprunt");
        } else {
            // Suppression du livre
            $stmt = $pdo->prepare("DELETE FROM livres WHERE id = ?");
            $stmt->execute([$id]);
            header("Location: liste_livres.php?success=supprime");
        }
    } catch (PDOException $e) {
        die("Erreur lors de la suppression : " . $e->getMessage());
    }
} else {
    header("Location: liste_livres.php");
}
exit;