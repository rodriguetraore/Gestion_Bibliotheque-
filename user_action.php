<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') { exit; }

$id = (int)$_GET['id'];
$type = $_GET['type'];

if ($type === 'promote') {
    $newRole = 'admin';
} elseif ($type === 'demote') {
    $newRole = 'etudiant';
}

if (isset($newRole)) {
    $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->execute([$newRole, $id]);
}

header("Location: liste_utilisateurs.php?msg=role_updated");
exit;