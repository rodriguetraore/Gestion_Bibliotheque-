<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id'])) { exit; }

$emprunt_id = (int)$_GET['id'];

// On récupère les infos de l'emprunt, du livre et de l'étudiant
$sql = "SELECT e.*, l.titre, l.auteur, u.nom, u.matricule, u.ecole 
        FROM emprunts e 
        JOIN livres l ON e.livre_id = l.id 
        JOIN users u ON e.user_id = u.id 
        WHERE e.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$emprunt_id]);
$data = $stmt->fetch();

if (!$data) { die("Reçu introuvable."); }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ticket d'emprunt - #<?= $data['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .ticket { max-width: 400px; margin: 20px auto; border: 1px dashed #000; padding: 20px; font-family: 'Courier New', Courier, monospace; }
        @media print {
            .no-print { display: none; }
            .ticket { border: none; margin: 0; }
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="text-center mb-3">
            <h4>HOOYIA-LIBRARY</h4>
            <p>HETEC BURKINA<br>Reçu d'emprunt officiel</p>
        </div>
        <hr>
        <p><strong>N° Emprunt :</strong> #<?= $data['id'] ?></p>
        <p><strong>Date :</strong> <?= date('d/m/Y H:i', strtotime($data['date_emprunt'])) ?></p>
        <hr>
        <p><strong>Étudiant :</strong> <?= htmlspecialchars($data['nom']) ?></p>
        <p><strong>Matricule :</strong> <?= htmlspecialchars($data['matricule']) ?></p>
        <p><strong>École :</strong> <?= htmlspecialchars($data['ecole']) ?></p>
        <hr>
        <p><strong>Livre :</strong> <?= htmlspecialchars($data['titre']) ?></p>
        <p><strong>Auteur :</strong> <?= htmlspecialchars($data['auteur']) ?></p>
        <hr>
        <div class="text-center mt-4">
            <p class="small">Merci de rendre le livre à temps.<br>Signature de la bibliothèque</p>
            <div style="height: 50px;"></div> </div>
    </div>

    <div class="text-center no-print mt-3">
        <button onclick="window.print()" class="btn btn-primary">Lancer l'impression</button>
        <button onclick="window.close()" class="btn btn-secondary">Fermer</button>
    </div>
</body>
</html>