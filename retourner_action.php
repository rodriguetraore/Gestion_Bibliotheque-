<?php
session_start();
require_once 'config/db.php';
require_once 'includes/check_admin.php'; // Seul l'admin peut valider un retour

if (isset($_GET['id'])) {
    $emprunt_id = (int)$_GET['id'];

    try {
        $pdo->beginTransaction();

        // 1. Récupérer l'ID du livre lié à cet emprunt (seulement s'il n'est pas déjà rendu)
        $stmt = $pdo->prepare("SELECT livre_id FROM emprunts WHERE id = ? AND date_retour IS NULL");
        $stmt->execute([$emprunt_id]);
        $emprunt = $stmt->fetch();

        if ($emprunt) {
            $livre_id = $emprunt['livre_id'];

            // 2. Marquer la date de retour comme "Maintenant"
            $sqlDate = "UPDATE emprunts SET date_retour = NOW() WHERE id = ?";
            $pdo->prepare($sqlDate)->execute([$emprunt_id]);

            // 3. Augmenter le stock du livre (+1)
            $sqlStock = "UPDATE livres SET stock = stock + 1 WHERE id = ?";
            $pdo->prepare($sqlStock)->execute([$livre_id]);

            $pdo->commit();
            header("Location: gestion_emprunts.php?msg=retour_succes");
            exit;
        } else {
            $pdo->rollBack();
            die("Erreur : Cet emprunt a déjà été retourné ou n'existe pas.");
        }

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Erreur lors du retour : " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit;
}