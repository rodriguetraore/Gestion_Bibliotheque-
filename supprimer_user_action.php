<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') { exit; }

$id = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: liste_utilisateurs.php?msg=user_deleted");
} catch (PDOException $e) {
    die("Erreur : Impossible de supprimer cet utilisateur (il a peut-être des emprunts en cours).");
}
exit;