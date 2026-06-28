<div class="max-w-lg mx-auto my-8 px-4">

    <div class="mb-6 pb-4 border-b border-gray-200">
        <h2 class="text-2xl font-bold text-gray-800">Modifier la catégorie</h2>
        <p class="text-sm text-gray-500 mt-1">
            Renommez la catégorie. Les articles associés seront automatiquement mis à jour.
        </p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <form method="POST"
              action="<?= path('admin', 'editCategorie') ?>"
              class="space-y-5">

            <input type="hidden" name="id_categorie" value="<?= $categorie['id_categorie'] ?>">

            <div>
                <label for="libelle" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nom de la catégorie
                </label>
                <input type="text" name="libelle" id="libelle"
                       value="<?= htmlspecialchars($_POST['libelle'] ?? $categorie['libelle']) ?>"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                <?php if (isset($errors['libelle'])): ?>
                    <span class="text-red-600 text-xs mt-1 block"><?= $errors['libelle'] ?></span>
                <?php endif; ?>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <a href="<?= path('admin', 'listeCategories') ?>"
                   class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Annuler
                </a>
                <button type="submit" name="btn_modifier"
                        class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>