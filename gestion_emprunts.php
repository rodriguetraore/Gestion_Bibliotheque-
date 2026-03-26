<?php if ($row['date_retour'] == NULL): ?>
    <a href="retourner_action.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">
        Valider le retour
    </a>
<?php else: ?>
    <span class="text-success">Rendu</span>
<?php endif; ?>