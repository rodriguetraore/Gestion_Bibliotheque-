<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
require_once 'db.php';

$totalLivres = $pdo->query("SELECT COUNT(*) FROM livres")->fetchColumn();
$emprunts = $pdo->query("SELECT COUNT(*) FROM emprunts WHERE date_retour IS NULL")->fetchColumn();
?>
<!DOCTYPE html>
<html>
<head><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container text-center">
        <h1>Tableau de Bord</h1>
        <div class="row mt-4">
            <div class="col-md-6"><div class="card bg-primary text-white p-3"><h3>Livres : <?= $totalLivres ?></h3></div></div>
            <div class="col-md-6"><div class="card bg-danger text-white p-3"><h3>Sorties : <?= $emprunts ?></h3></div></div>
        </div>
    </div>
</body>
</html>