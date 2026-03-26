<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT titre FROM livres WHERE id = ?");
$stmt->execute([$id]);
$livre = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Confirmation</title>
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">
    <div class="container text-center">
        <div class="card shadow-lg mx-auto p-5" style="max-width: 500px; border-radius: 20px;">
            <h1 class="display-4">📖</h1>
            <h2 class="mb-4">Confirmer l'emprunt</h2>
            <p class="lead">Voulez-vous vraiment emprunter le livre :<br><strong>"<?= htmlspecialchars($livre['titre']) ?>"</strong> ?</p>
            
            <div class="d-flex justify-content-center gap-3 mt-4">
                <a href="emprunter_action.php?id=<?= $id ?>" class="btn btn-success btn-lg">Oui, confirmer</a>
                
                <a href="liste_livres.php" class="btn btn-secondary btn-lg px-4">Non, annuler</a>
            </div>
        </div>
    </div>
</body>
</html>