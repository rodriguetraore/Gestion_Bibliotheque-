<?php
session_start();
require_once 'db.php';

// Sécurité : Seul l'admin peut accéder à cette page
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT titre FROM livres WHERE id = ?");
$stmt->execute([$id]);
$livre = $stmt->fetch();

if (!$livre) { die("Livre introuvable."); }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Confirmation de suppression</title>
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">
    <div class="container text-center">
        <div class="card shadow-lg mx-auto p-5 border-danger" style="max-width: 550px; border-radius: 20px;">
            <h1 class="display-4 text-danger">⚠️</h1>
            <h2 class="mb-4 text-danger">Attention : Action irréversible</h2>
            <p class="lead">Voulez-vous vraiment supprimer définitivement le livre :<br>
               <span class="badge bg-dark fs-5 mt-2"><?= htmlspecialchars($livre['titre']) ?></span>
            </p>
            
            <div class="alert alert-warning small mt-3">
                Cela supprimera également l'historique des emprunts liés à ce livre.
            </div>
            
            <div class="d-flex justify-content-center gap-3 mt-4">
                <a href="supprimer_livre.php?id=<?= $id ?>" class="btn btn-danger btn-lg px-4">Oui, supprimer</a>
                
                <a href="liste_livres.php" class="btn btn-secondary btn-lg px-4">Non, annuler</a>
            </div>
        </div>
    </div>
</body>
</html>