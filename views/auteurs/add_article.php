<div class="max-w-3xl mx-auto my-8 bg-white rounded-xl shadow-md border border-gray-100 p-6 md:p-8">
    <!-- En-tête -->
    <div class="mb-8 border-b border-gray-100 pb-4">
        <h2 class="text-2xl font-bold text-gray-800">Rédiger un nouvel article</h2>
        <p class="text-sm text-gray-500 mt-1">Proposez un article qui sera soumis à la validation de l'administrateur.</p>
    </div>

    <!-- Formulaire -->
    <form action="index.php?controller=auteur&action=add" method="POST" enctype="multipart/form-data" class="space-y-6">
        
        <!-- Titre -->
        <div>
            <label for="titre" class="block text-sm font-semibold text-gray-700 mb-2">Titre de l'article</label>
            <input type="text" name="titre" id="titre" 
                   value="<?= htmlspecialchars($_POST['titre'] ?? '') ?>"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                   placeholder="Ex: Les nouveautés de PHP en 2026">
            <?php if(isset($errors['titre'])): ?>
                <span class="text-red-600 text-xs mt-1 block font-medium"> <?= $errors['titre'] ?></span>
            <?php endif; ?>
        </div>
          <!-- Image / Photo de couverture -->
      <div>
        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Photo de couverture</label>
        <input type="file" name="image" id="image" accept="image/*"
                           value="<?= htmlspecialchars($_POST['image'] ?? '') ?>"
               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-300 rounded-lg p-1.5 focus:ring-2 focus:ring-indigo-500 outline-none transition">
        <span class="text-red-800 text-xs"> <?=$errors["image"] ?? "" ?></span>
      </div>

        <!-- Catégorie -->
        <div>
            <label for="categorie_id" class="block text-sm font-semibold text-gray-700 mb-2">Catégorie</label>
            <select name="categorie_id" id="categorie_id" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none bg-white transition">
                <option value="">-- Choisir une catégorie --</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat['id_categorie'] ?>" <?= (isset($_POST['categorie_id']) && $_POST['categorie_id'] == $cat['id_categorie']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['libelle']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if(isset($errors['categorie_id'])): ?>
                <span class="text-red-600 text-xs mt-1 block font-medium"> <?= $errors['categorie_id'] ?></span>
            <?php endif; ?>
        </div>

        <!-- Contenu -->
        <div>
            <label for="contenu" class="block text-sm font-semibold text-gray-700 mb-2">Contenu de l'article</label>
            <textarea name="contenu" id="contenu" rows="8" 
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition resize-y"
                      placeholder="Écrivez le corps de votre article ici..."><?= htmlspecialchars($_POST['contenu'] ?? '') ?></textarea>
            <?php if(isset($errors['contenu'])): ?>
                <span class="text-red-600 text-xs mt-1 block font-medium"> <?= $errors['contenu'] ?></span>
            <?php endif; ?>
        </div>

        <!-- Boutons d'action -->
        <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100">
            <a href="index.php?controller=auteur&action=liste" 
               class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                Annuler
            </a>
            <button type="submit" name="btn_publier" 
                    class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition shadow-sm">
                Enregistrer l'article
            </button>
        </div>
    </form>
</div>