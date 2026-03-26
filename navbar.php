<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php">g_bibliotheque</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
    <li class="nav-item">
        <a class="nav-link" href="liste_livres.php">Livres</a>
    </li>
    
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
    <li class="nav-item">
        <a class="nav-link text-info" href="liste_utilisateurs.php">👥 Membres</a>
    </li>
    <li class="nav-item">
    <a class="nav-link" href="tous_emprunts.php">📜 Historique</a>
</li>
    <li class="nav-item">
        <a class="nav-link" href="admin_stats.php">📊 Stats</a>
    </li>
    <?php else: ?>
    <li class="nav-item">
        <a class="nav-link" href="mes_emprunts.php">Mes Emprunts</a>
    </li>
    <?php endif; ?>
</ul> 
     <span class="navbar-text me-3 text-white">
    <?= htmlspecialchars($_SESSION['user_nom'] ?? 'Utilisateur') ?> 
    (<?= strtoupper($_SESSION['user_role'] ?? 'INVITÉ') ?>)
</span>
      <a href="logout.php" class="btn btn-outline-danger btn-sm">Déconnexion</a>
    </div>
  </div>
</nav>