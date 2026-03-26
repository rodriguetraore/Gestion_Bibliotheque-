<?php
session_start();
require_once 'db.php';

// Sécurité Admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Requête SQL pour tout voir : Qui a pris quoi et quand
$sql = "SELECT e.id, u.nom as etudiant, l.titre as livre, e.date_emprunt, e.date_retour 
        FROM emprunts e
        JOIN users u ON e.user_id = u.id
        JOIN livres l ON e.livre_id = l.id
        ORDER BY e.date_emprunt DESC";

$stmt = $pdo->query($sql);
$tous_emprunts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique Global - Hooyia Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>📜 Historique Global des Emprunts</h2>
            <span class="badge bg-dark"><?= count($tous_emprunts) ?> transactions au total</span>
        </div>

        <div class="table-responsive bg-white p-3 shadow-sm rounded">
            <table class="table table-hover">
                <thead class="table-secondary">
                    <tr>
                        <th>Date</th>
                        <th>Étudiant</th>
                        <th>Livre</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($tous_emprunts as $te): ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($te['date_emprunt'])) ?></td>
                        <td><strong><?= htmlspecialchars($te['etudiant']) ?></strong></td>
                        <td><?= htmlspecialchars($te['livre']) ?></td>
                        <td>
                            <?php if($te['date_retour']): ?>
                                <span class="badge bg-success">Rendu le <?= date('d/m/Y', strtotime($te['date_retour'])) ?></span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">📖 En cours de lecture</span>
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