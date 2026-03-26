<?php
session_start();
require_once 'db.php';

// Sécurité : Uniquement pour l'Admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Récupérer tous les utilisateurs sauf soi-même (pour ne pas se supprimer par erreur)
$stmt = $pdo->prepare("SELECT * FROM users WHERE id != ? ORDER BY nom ASC");
$stmt->execute([$_SESSION['user_id']]);
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Utilisateurs - g_bibliotheque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h2 class="mb-4">👥 Gestion des Membres</h2>
        
        <div class="table-responsive bg-white p-3 shadow-sm rounded">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nom & Prénom</th>
                        <th>Matricule</th>
                        <th>Email</th>
                        <th>Rôle actuel</th>
                        <th>Date d'inscription</th>
                        <th>Livres empruntés</th>
                        <th class="text-center">Actions de sécurité</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $u): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($u['nom']) ?></strong></td>
                        <td><?= htmlspecialchars($u['matricule']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <span class="badge <?= $u['role'] === 'admin' ? 'bg-danger' : 'bg-primary' ?>">
                                <?= strtoupper($u['role']) ?>
                            </span>
                        </td>
                        <td><?= isset($u['date_inscription']) ? date('d/m/Y', strtotime($u['date_inscription'])) : '-' ?></td>
                        <td>
    <?php 
    if (!empty($u['livres_empruntes'])) {
        echo '<span class="badge bg-info">' . htmlspecialchars($u['livres_empruntes']) . '</span>';
    } else {
        echo '<small class="text-muted">Aucun emprunt</small>';
    }
    ?>
</td>
                        <td class="text-center">
                            <?php if($u['role'] === 'etudiant'): ?>
                                <a href="user_action.php?id=<?= $u['id'] ?>&type=promote" class="btn btn-sm btn-outline-success">Promouvoir Admin</a>
                            <?php else: ?>
                                <a href="user_action.php?id=<?= $u['id'] ?>&type=demote" class="btn btn-sm btn-outline-warning">Retirer Admin</a>
                            <?php 'document.write'(''); ?>
                            <?php endif; ?>

                            <a href="confirmer_suppression_user.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger">Supprimer</a>
                        </td>


                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>