<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// On utilise db.php en direct (pas de dossier config)
require_once 'db.php'; 

// Sécurité : Vérifier si l'utilisateur est admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php?error=acces_refuse");
    exit;
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = htmlspecialchars($_POST['titre']);
    $auteur = htmlspecialchars($_POST['auteur']);
    $stock = (int)$_POST['stock'];

    if (!empty($titre) && !empty($auteur)) {
        $sql = "INSERT INTO livres (titre, auteur, stock) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$titre, $auteur, $stock])) {
            $success = "Le livre '$titre' a été ajouté avec succès !";
        } else {
            $error = "Erreur lors de l'ajout.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Livre - Hooyia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">➕ Ajouter un nouveau livre</h4>
                    </div>
                    <div class="card-body">
                        <?php if($success): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                        <?php endif; ?>
                        
                        <?php if($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Titre du livre</label>
                                <input type="text" name="titre" class="form-control" placeholder="Ex: Architecture des ordinateurs" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Auteur</label>
                                <input type="text" name="auteur" class="form-control" placeholder="Ex: Andrew Tanenbaum" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nombre d'exemplaires (Stock)</label>
                                <input type="number" name="stock" class="form-control" value="1" min="1" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-dark">Enregistrer en bibliothèque</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="liste_livres.php" class="btn btn-link text-secondary">← Voir la liste des livres</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>