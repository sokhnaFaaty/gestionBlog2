<?php /** @var array $admin */ ?>
<?php $suffixe = $suffixe ?? ''; ?>
<?php $estMoi = (int)$admin['id_utilisateur'] === (int)($_SESSION['user']['id_utilisateur'] ?? 0); ?>
<?php if ($estMoi): ?>
    <span class="text-gray-300 cursor-not-allowed" title="Vous ne pouvez pas supprimer votre propre compte">
        <i class="fas fa-trash-alt"></i>
    </span>
<?php else: ?>
    <form id="form-supprimer-admin-<?= $admin['id_utilisateur'] ?>-<?= $suffixe ?>"
          method="POST"
          action="<?= path('utilisateur', 'supprimerAdmin') ?>"
          class="hidden">
        <input type="hidden" name="id_utilisateur" value="<?= $admin['id_utilisateur'] ?>">
    </form>
    <button type="button"
            onclick="confirmerAction(this)"
            data-form="form-supprimer-admin-<?= $admin['id_utilisateur'] ?>-<?= $suffixe ?>"
            data-message="Supprimer l'administrateur <?= htmlspecialchars($admin['prenom'] . ' ' . $admin['nom'], ENT_QUOTES) ?> ?"
            class="text-gray-400 hover:text-red-600 transition"
            title="Supprimer">
        <i class="fas fa-trash-alt"></i>
    </button>
<?php endif; ?>
