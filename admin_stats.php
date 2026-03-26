<?php
session_start();
require_once 'db.php';

// Sécurité Admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
// Compter les emprunts dont la date limite est dépassée et qui ne sont pas encore rendus
$retardsCount = $pdo->query("SELECT COUNT(*) FROM emprunts WHERE date_retour IS NULL AND date_limite < NOW()")->fetchColumn();

// --- COLLECTE DES DONNÉES ---

// 1. Nombre total de livres en stock (Somme de la colonne stock)
$totalLivres = $pdo->query("SELECT SUM(stock) FROM livres")->fetchColumn() ?: 0;

// 2. Nombre d'emprunts actifs (ceux qui n'ont pas encore été rendus)
$empruntsActifs = $pdo->query("SELECT COUNT(*) FROM emprunts WHERE date_retour IS NULL")->fetchColumn();

// 3. Nombre total d'étudiants inscrits
$totalEtudiants = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'etudiant'")->fetchColumn();

// 4. Le livre le plus populaire (celui qui a le plus d'entrées dans la table emprunts)
$sqlPop = "SELECT l.titre, COUNT(e.id) as nb 
           FROM livres l 
           JOIN emprunts e ON l.id = e.livre_id 
           GROUP BY l.id 
           ORDER BY nb DESC LIMIT 1";
$livrePop = $pdo->query($sqlPop)->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - g_bibliotheque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-stat { border: none; border-radius: 15px; transition: transform 0.3s; }
        .card-stat:hover { transform: translateY(-5px); }
    </style>
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="mb-4">📊 Tableau de Bord Administrateur</h2>
        
        <div class="row g-4 text-white">
            <div class="col-md-4">
                <div class="card card-stat bg-primary p-4 shadow">
                    <h5>📚 Total Livres</h5>
                    <h2 class="display-4 fw-bold"><?= $totalLivres ?></h2>
                    <p class="mb-0">Unités en rayon</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-stat bg-success p-4 shadow">
                    <h5>📖 Emprunts en cours</h5>
                    <h2 class="display-4 fw-bold"><?= $empruntsActifs ?></h2>
                    <p class="mb-0">Livres non encore rendus</p>
                </div>
            </div>

            <div class="col-md-3">
    <div class="card card-stat bg-danger text-white p-4 shadow">
        <h5>⚠️ Retards Critiques</h5>
        <h2 class="display-4 fw-bold"><?= $retardsCount ?></h2>
        <p class="mb-0">Livres hors délai</p>
    </div>
</div>

            <div class="col-md-4">
                <div class="card card-stat bg-warning text-dark p-4 shadow">
                    <h5>👥 Étudiants</h5>
                    <h2 class="display-4 fw-bold"><?= $totalEtudiants ?></h2>
                    <p class="mb-0">Membres actifs</p>
                </div>
            </div>
        </div>

        <div class="mt-5 p-4 bg-white rounded shadow-sm border-start border-primary border-5">
            <h4>🏆 Livre le plus demandé</h4>
            <?php if($livrePop): ?>
                <p class="lead mb-0">
                    Le titre <strong>"<?= htmlspecialchars($livrePop['titre']) ?>"</strong> 
                    a été emprunté <strong><?= $livrePop['nb'] ?> fois</strong>.
                </p>
            <?php else: ?>
                <p class="text-muted">Aucun emprunt enregistré pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>