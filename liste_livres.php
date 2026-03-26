<?php
session_start();
require_once 'db.php';

// 1. On vérifie si une recherche a été effectuée
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

if (!empty($search)) {
    // Recherche avec filtre (Le % permet de trouver le mot n'importe où dans le texte)
    $sql = "SELECT * FROM livres WHERE titre LIKE ? OR auteur LIKE ? ORDER BY titre ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    // Affichage normal si aucune recherche
    $stmt = $pdo->query("SELECT * FROM livres ORDER BY titre ASC");
}

$livres = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Livres disponibles - Hooyia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h2 class="mb-4">📚 Livres disponibles</h2>

        <form method="GET" action="liste_livres.php" class="row g-3 mb-4">
    <div class="col-md-10">
        <input type="text" name="search" class="form-control" 
               placeholder="Rechercher par titre ou auteur..." 
               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">🔍 Rechercher</button>
    </div>
</form>
        
        <div class="table-responsive bg-white p-3 shadow-sm rounded">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Stock</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($livres as $l): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($l['titre']) ?></strong></td>
                        <td><?= htmlspecialchars($l['auteur']) ?></td>
                        <td>
    <?php if($l['stock'] > 0): ?>
        <span class="badge bg-info text-dark"><?= $l['stock'] ?> en réserve</span>
    <?php else: ?>
        <span class="badge bg-danger">Épuisé</span>
    <?php endif; ?>

    <?php if($_SESSION['user_role'] === 'admin'): ?>
        <div class="mt-2">
            <a href="ajuster_stock.php?id=<?= $l['id'] ?>&action=plus" class="btn btn-xs btn-outline-success py-0 px-1">+</a>
            <a href="ajuster_stock.php?id=<?= $l['id'] ?>&action=moins" class="btn btn-xs btn-outline-danger py-0 px-1">-</a>
        </div>
    <?php endif; ?>
</td>
                        <td class="text-center">
    <?php if ($_SESSION['user_role'] === 'admin'): ?>
        <div class="d-flex justify-content-center gap-2">
            <a href="modifier_livre.php?id=<?= $l['id'] ?>" class="btn btn-warning btn-sm">
               ✏️ Modifier
            </a>
            
            <a href="confirmer_suppression.php?id=<?= $l['id'] ?>" class="btn btn-danger btn-sm">
   🗑️ Supprimer
</a>
        </div>
    <?php else: ?>
        <?php if($l['stock'] > 0): ?>
            <a href="confirmer_emprunt.php?id=<?= $l['id'] ?>" class="btn btn-success btn-sm">
               📖 Emprunter
            </a>
        <?php else: ?>
            <span class="badge bg-secondary">Indisponible</span>
        <?php endif; ?>
    <?php endif; ?>
</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>