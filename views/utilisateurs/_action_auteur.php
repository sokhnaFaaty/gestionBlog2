<?php /** @var array $auteur */ ?>
<?php $suffixe = $suffixe ?? ''; ?>
<form id="form-banir-<?= $auteur['id_utilisateur'] ?>-<?= $suffixe ?>"
      method="POST"
      action="<?= path('utilisateur', 'banirAuteur') ?>"
      class="hidden">
    <input type="hidden" name="id_utilisateur" value="<?= $auteur['id_utilisateur'] ?>">
</form>
<button type="button"
        onclick="confirmerAction(this)"
        data-form="form-banir-<?= $auteur['id_utilisateur'] ?>-<?= $suffixe ?>"
        data-message="<?= $auteur['banni'] ? 'Débannir' : 'Bannir' ?> l'auteur <?= htmlspecialchars($auteur['nom'], ENT_QUOTES) ?> ?"
        class="px-3 py-1.5 text-xs font-medium rounded-lg border transition
            <?= $auteur['banni']
                ? 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100'
                : 'bg-red-50 text-red-700 border-red-200 hover:bg-red-100' ?>">
    <?= $auteur['banni'] ? 'Débannir' : 'Bannir' ?>
</button>
