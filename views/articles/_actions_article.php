<?php /** @var array $art */ ?>
<?php $suffixe = $suffixe ?? ''; ?>
<!-- Publier -->
<?php if ($art['statut'] !== 'Publie'): ?>
<form method="POST" action="<?= path('article', 'changerStatut') ?>">
    <input type="hidden" name="id_article" value="<?= $art['id_article'] ?>">
    <input type="hidden" name="statut" value="Publie">
    <button class="px-3 py-1.5 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition">Publier</button>
</form>
<?php endif; ?>
<!-- Rejeter -->
<?php if ($art['statut'] !== 'Rejete'): ?>
<form method="POST" action="<?= path('article', 'changerStatut') ?>">
    <input type="hidden" name="id_article" value="<?= $art['id_article'] ?>">
    <input type="hidden" name="statut" value="Rejete">
    <button class="px-3 py-1.5 bg-red-500 text-white text-xs rounded-lg hover:bg-red-600 transition">Rejeter</button>
</form>
<?php endif; ?>
<!-- Bannir si signalements >= 5 -->
<?php if ((int)$art['nb_signalements'] >= 5 && $art['statut'] !== 'Rejete'): ?>
<form id="form-banir-art-<?= $art['id_article'] ?>-<?= $suffixe ?>"
      method="POST" action="<?= path('article', 'banir') ?>" class="hidden">
    <input type="hidden" name="id_article" value="<?= $art['id_article'] ?>">
</form>
<button type="button"
        onclick="confirmerAction(this)"
        data-form="form-banir-art-<?= $art['id_article'] ?>-<?= $suffixe ?>"
        data-message="Bannir cet article signalé ?"
        class="px-3 py-1.5 bg-orange-600 text-white text-xs rounded-lg hover:bg-orange-700 transition">
        Bannir
</button>
<?php endif; ?>
