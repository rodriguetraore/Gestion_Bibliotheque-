<?php
session_start();
require_once 'db.php';

// Debug : voir si on reçoit bien l'ID
if (!isset($_GET['id'])) {
    die("Erreur : Aucun ID de livre reçu. Vérifie ton lien dans confirmer_emprunt.php");
}

$livre_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

try {
    // 1. Vérifier si le livre existe et s'il y a du stock
    $stmt = $pdo->prepare("SELECT stock FROM livres WHERE id = ?");
    $stmt->execute([$livre_id]);
    $livre = $stmt->fetch();

    if ($livre && $livre['stock'] > 0) {
        $pdo->beginTransaction();

        // 2. Insérer l'emprunt
        $sql1 = "INSERT INTO emprunts (user_id, livre_id, date_emprunt) VALUES (?, ?, NOW())";
        $pdo->prepare($sql1)->execute([$user_id, $livre_id]);

        // 3. Diminuer le stock
        $sql2 = "UPDATE livres SET stock = stock - 1 WHERE id = ?";
        $pdo->prepare($sql2)->execute([$livre_id]);

        $pdo->commit();
        
        // Redirection vers l'espace étudiant
        header("Location: mes_emprunts.php?success=emprunt_ok");
        exit;
    } else {
        die("Erreur : Le stock est déjà à 0 ou le livre n'existe pas.");
    }

} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    die("Erreur SQL fatale : " . $e->getMessage());
}
?>