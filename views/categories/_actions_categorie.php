<?php /** @var array $cat */ ?>
<?php $suffixe = $suffixe ?? ''; ?>
<!-- Modifier -->
<a href="<?= path('categorie', 'edit', ['id' => $cat['id_categorie']]) ?>"
   class="px-3 py-1.5 text-xs font-medium rounded-lg border bg-indigo-50 text-indigo-700 border-indigo-200 hover:bg-indigo-100 transition">
    Modifier
</a>
<!-- Supprimer — désactivé si la catégorie a des articles -->
<?php if ((int)$cat['nb_articles'] === 0): ?>
<form id="form-supprimer-cat-<?= $cat['id_categorie'] ?>-<?= $suffixe ?>"
      method="POST"
      action="<?= path('categorie', 'supprimer') ?>"
      class="hidden">
    <input type="hidden" name="id_categorie" value="<?= $cat['id_categorie'] ?>">
</form>
<button type="button"
        onclick="confirmerAction(this)"
        data-form="form-supprimer-cat-<?= $cat['id_categorie'] ?>-<?= $suffixe ?>"
        data-message="Supprimer la catégorie « <?= htmlspecialchars($cat['libelle'], ENT_QUOTES) ?> » ?"
        class="px-3 py-1.5 text-xs font-medium rounded-lg border bg-red-50 text-red-700 border-red-200 hover:bg-red-100 transition">
    Supprimer
</button>
<?php else: ?>
<span class="px-3 py-1.5 text-xs font-medium rounded-lg border bg-gray-50 text-gray-400 border-gray-200 cursor-not-allowed"
      title="Impossible de supprimer une catégorie utilisée par des articles">
    Supprimer
</span>
<?php endif; ?>
