<div class="max-w-3xl mx-auto my-8 bg-white rounded-xl shadow-md border border-gray-100 p-6 md:p-8">
    <div class="mb-8 border-b border-gray-100 pb-4">
        <h2 class="text-2xl font-bold text-gray-800">Modifier l'article</h2>
        <p class="text-sm text-gray-500 mt-1">
            Toute modification repassera l'article en statut
            <span class="font-medium text-yellow-600">En attente</span>.
        </p>
    </div>

    <form action="index.php?controller=auteur&action=edit" method="POST" enctype="multipart/form-data" class="space-y-6" class="space-y-6 ">
        <input type="hidden" name="id_article" value="<?= $article['id_article'] ?>">

        <!-- Titre -->
        <div>
            <label for="titre" class="block text-sm font-semibold text-gray-700 mb-2">Titre</label>
            <input type="text" name="titre" id="titre"
                   value="<?= htmlspecialchars($_POST['titre'] ?? $article['titre']) ?>"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
            <?php if (isset($errors['titre'])): ?>
                <span class="text-red-600 text-xs mt-1 block"><?= $errors['titre'] ?></span>
            <?php endif; ?>
        </div>

        <!-- Image -->
        <div>
            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Photo de couverture</label>
            <input type="file" name="image" id="image" accept="image/*"
                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-300 rounded-lg p-1.5 focus:ring-2 focus:ring-indigo-500 outline-none transition">
            <?php if (isset($errors['image'])): ?>
                <span class="text-red-800 text-xs"><?= $errors['image'] ?></span>
            <?php endif; ?>
        </div>
   
        <!-- Catégorie -->
        <div>
            <label for="categorie_id" class="block text-sm font-semibold text-gray-700 mb-2">Catégorie</label>
            <select name="categorie_id" id="categorie_id"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-white transition">
                <option value="">-- Choisir une catégorie --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id_categorie'] ?>"
                        <?= (($_POST['categorie_id'] ?? $article['id_categorie']) == $cat['id_categorie']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['libelle']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['categorie_id'])): ?>
                <span class="text-red-600 text-xs mt-1 block"><?= $errors['categorie_id'] ?></span>
            <?php endif; ?>
        </div>

        <!-- Contenu -->
        <div>
            <label for="contenu" class="block text-sm font-semibold text-gray-700 mb-2">Contenu</label>
            <textarea name="contenu" id="contenu" rows="8"
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition resize-y"><?= htmlspecialchars($_POST['contenu'] ?? $article['contenu']) ?></textarea>
            <?php if (isset($errors['contenu'])): ?>
                <span class="text-red-600 text-xs mt-1 block"><?= $errors['contenu'] ?></span>
            <?php endif; ?>
        </div>

        <!-- Barre d'actions -->
        <div class="flex items-center justify-between pt-4 border-t border-gray-100">

            <!-- Bouton Supprimer — soumet form-delete via l'attribut form= -->
            <button type="submit"
                    form="form-delete"
                    onclick="return confirm('Supprimer définitivement cet article ?')"
                    class="px-4 py-2.5 bg-red-50 text-red-700 border border-red-200 text-sm font-medium rounded-lg hover:bg-red-100 transition">
                Supprimer
            </button>

            <div class="flex gap-3">
                <a href="index.php?controller=auteur&action=liste"
                   class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Annuler
                </a>
                <button type="submit" name="btn_modifier"
                        class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
                    Enregistrer
                </button>
            </div>
        </div>
    </form>

    <!-- Formulaire de suppression  -->
    <form id="form-delete"
          method="POST"
          action="index.php?controller=auteur&action=delete">
        <input type="hidden" name="id_article" value="<?= $article['id_article'] ?>">
    </form>
</div>