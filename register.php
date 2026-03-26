<?php
require_once 'db.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricule = htmlspecialchars($_POST['matricule']);
    $email = htmlspecialchars($_POST['email']);
    $nom = htmlspecialchars($_POST['nom']);
    $ecole = $_POST['ecole'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO users (matricule, email, nom, ecole, password, role) VALUES (?, ?, ?, ?, ?, 'etudiant')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$matricule, $email, $nom, $ecole, $password]);
        header("Location: login.php?msg=inscrit");
        exit;
    } catch (PDOException $e) {
        $error = "Ce matricule est déjà utilisé.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Rejoindre g_bibliotheque</title>
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 450px;">
    <div class="card shadow border-0">
        <div class="card-body p-4">
            <h3 class="text-center mb-4">Créer un compte Étudiant</h3>
            <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="POST">
                <input type="text" name="matricule" class="form-control mb-3" placeholder="Votre Matricule HETEC" required>
                <input type="email" name="email" class="form-control mb-3" placeholder="Email institutionnel" required>
                <input type="text" name="nom" class="form-control mb-3" placeholder="Nom et Prénom" required>
                <select name="ecole" class="form-select mb-3">
                    <option value="HETEC">HETEC Burkina</option>
                    <option value="UJKZ">UJKZ</option>
                    <option value="Aube Nouvelle">Aube Nouvelle</option>
                </select>
                <input type="password" name="password" class="form-control mb-4" placeholder="Créer un mot de passe" required>
                <button type="submit" class="btn btn-primary w-100">S'inscrire maintenant</button>
            </form>
            <p class="text-center mt-3"><a href="login.php" class="text-decoration-none text-muted">Déjà inscrit ? Connexion</a></p>
        </div>
    </div>
</div>
</body>
</html>