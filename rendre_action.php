<?php
session_start();
require_once 'db.php';

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $emprunt_id = (int)$_GET['id'];
    $user_id = $_SESSION['user_id'];

    try {
        // 1. On récupère l'ID du livre lié à cet emprunt
        $stmt = $pdo->prepare("SELECT livre_id FROM emprunts WHERE id = ? AND user_id = ? AND date_retour IS NULL");
        $stmt->execute([$emprunt_id, $user_id]);
        $emprunt = $stmt->fetch();

        if ($emprunt) {
            $livre_id = $emprunt['livre_id'];

            // Début de la transaction pour garantir l'intégrité des données
            $pdo->beginTransaction();

            // 2. Mettre à jour la date de retour
            $updateEmprunt = $pdo->prepare("UPDATE emprunts SET date_retour = NOW() WHERE id = ?");
            $updateEmprunt->execute([$emprunt_id]);

            // 3. Augmenter le stock du livre (+1)
            $updateStock = $pdo->prepare("UPDATE livres SET stock = stock + 1 WHERE id = ?");
            $updateStock->execute([$livre_id]);

            $pdo->commit();
            header("Location: mes_emprunts.php?msg=rendu_ok");
        } else {
            header("Location: mes_emprunts.php?error=deja_rendu");
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Erreur lors du retour : " . $e->getMessage());
    }
} else {
    header("Location: mes_emprunts.php");
}
exit;