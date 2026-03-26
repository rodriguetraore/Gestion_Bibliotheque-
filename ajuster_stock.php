<?php
session_start();
require_once 'db.php';

// Sécurité : Seul l'admin peut ajuster manuellement
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];

    if ($action === 'plus') {
        $sql = "UPDATE livres SET stock = stock + 1 WHERE id = ?";
    } elseif ($action === 'moins') {
        // On empêche le stock de descendre en dessous de 0
        $sql = "UPDATE livres SET stock = GREATEST(0, stock - 1) WHERE id = ?";
    }

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        header("Location: liste_livres.php?msg=stock_mis_a_jour");
    } catch (PDOException $e) {
        die("Erreur de mise à jour : " . $e->getMessage());
    }
} else {
    header("Location: liste_livres.php");
}
exit;