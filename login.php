<?php
session_start();
include 'db.php'; // Vérifie que ce fichier existe bien dans ton dossier

if (isset($_POST['btn_login'])) {
    $matricule = $_POST['matricule'];
    $password = $_POST['password'];

    // Requête pour vérifier l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM users WHERE matricule = ?");
    $stmt->execute([$matricule]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];

        // Redirection selon le rôle
        if ($user['role'] == 'admin') {
            header("Location: admin_stats.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $error = "Matricule ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - g_bibliotheque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: url('bibliotheque.jpg') no-repeat center center fixed; /* Vérifie le nom de ton image */
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-card {
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            color: white;
            width: 100%;
            max-width: 400px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
        }
        .input-group-text { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; }
        .form-control { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2 class="text-center mb-4">Connexion</h2>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger p-2 small text-center"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" name="matricule" class="form-control" placeholder="Matricule" required>
                </div>
            </div>
            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
                </div>
            </div>
            <button type="submit" name="btn_login" class="btn btn-primary w-100 py-2 fw-bold">Se connecter</button>
        </form>

        <div class="text-center mt-3">
            <p class="small text-white-50">
                Pas encore inscrit ? <a href="register.php" class="text-info fw-bold">Créer un compte</a>
            </p>
        </div>
    </div>
</body>
</html>