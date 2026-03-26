<?php
session_start();
require_once 'db.php';

// Sécurité : Seul l'admin accède à cette page
if ($_SESSION['user_role'] !== 'admin') { header("Location: index.php"); exit; }

$error = "";
$success = "";

// 1. Récupérer les infos actuelles du livre
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
    $stmt->execute([$id]);
    $livre = $stmt->fetch();
}

// 2. Traiter la modification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = htmlspecialchars($_POST['titre']);
    $auteur = htmlspecialchars($_POST['auteur']);
    $stock = (int)$_POST['stock'];
    $id = $_POST['id'];

    try {
        $sql = "UPDATE livres SET titre = ?, auteur = ?, stock = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$titre, $auteur, $stock, $id]);
        $success = "Le livre a été mis à jour avec succès !";
        // Actualiser les infos pour l'affichage
        $livre['titre'] = $titre; $livre['auteur'] = $auteur; $livre['stock'] = $stock;
    } catch (PDOException $e) {
        $error = "Erreur lors de la modification : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Modifier un livre</title>
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <div class="card shadow mx-auto" style="max-width: 500px;">
            <div class="card-header bg-warning"><h4>Modifier le livre</h4></div>
            <div class="card-body">
                <?php if($success) echo "<div class='alert alert-success'>$success <a href='liste_livres.php'>Retour</a></div>"; ?>
                <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
                
                <form method="POST">
                    <input type="hidden" name="id" value="<?= $livre['id'] ?>">
                    <div class="mb-3">
                        <label>Titre</label>
                        <input type="text" name="titre" class="form-control" value="<?= htmlspecialchars($livre['titre']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Auteur</label>
                        <input type="text" name="auteur" class="form-control" value="<?= htmlspecialchars($livre['auteur']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Stock</label>
                        <input type="number" name="stock" class="form-control" value="<?= $livre['stock'] ?>" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Enregistrer les modifications</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>