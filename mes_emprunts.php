<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$user_id = $_SESSION['user_id'];
$sql = "SELECT e.id, l.titre, l.auteur, e.date_emprunt, e.date_retour 
        FROM emprunts e 
        JOIN livres l ON e.livre_id = l.id 
        WHERE e.user_id = ? 
        ORDER BY e.date_emprunt DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$emprunts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Mon Espace - Hooyia</title>
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>📚 Mes lectures en cours</h2>
            <a href="liste_livres.php" class="btn btn-outline-primary">Emprunter un autre livre</a>
        </div>

        <?php if (empty($emprunts)): ?>
            <div class="card p-5 text-center border-0 shadow-sm">
                <p class="text-muted">Vous n'avez aucun emprunt pour le moment.</p>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach($emprunts as $e): ?>
<div class="col-md-6 mb-3">
    <div class="card shadow-sm border-start border-primary border-4">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($e['titre']) ?></h5>
            <p class="card-text text-muted mb-2">Auteur : <?= htmlspecialchars($e['auteur']) ?></p>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-secondary">Pris le : <?= date('d/m/Y', strtotime($e['date_emprunt'])) ?></small>
                
                <?php if($e['date_retour']): ?>
                    <span class="badge bg-success">Rendu le <?= date('d/m/Y', strtotime($e['date_retour'])) ?></span>
                <?php else: ?>
                    <a href="rendre_action.php?id=<?= $e['id'] ?>" 
                       class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Confirmez-vous avoir déposé le livre à la bibliothèque ?')">
                       Rendre le livre
                       <a href="ticket_emprunt.php?id=<?= $e['id'] ?>" target="_blank" class="btn btn-sm btn-outline-dark">
   🖨️ Imprimer reçu
</a>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>