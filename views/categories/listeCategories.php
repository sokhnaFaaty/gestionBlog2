<div class="max-w-4xl mx-auto my-8 px-4">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 pb-4 border-b border-gray-200 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Gestion des catégories</h2>
            <p class="text-sm text-gray-500 mt-1">Ajoutez, modifiez ou supprimez les catégories d'articles.</p>
        </div>
        <?php viewToggle('categories-admin'); ?>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Formulaire ajout -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-800 mb-4">Nouvelle catégorie</h3>
                <form method="POST" action="<?= path('categorie', 'liste') ?>" class="space-y-4">
                    <div>
                        <label for="libelle" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                        <input type="text" name="libelle" id="libelle"
                               value="<?= htmlspecialchars($_POST['libelle'] ?? '') ?>"
                               placeholder="Ex: Technologie"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        <?php if (isset($errors['libelle'])): ?>
                            <span class="text-red-600 text-xs mt-1 block"><?= $errors['libelle'] ?></span>
                        <?php endif; ?>
                    </div>
                    <button type="submit" name="btn_ajouter"
                            class="w-full px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                        Ajouter
                    </button>
                </form>
            </div>
        </div>

        <!-- Liste des catégories -->
        <div class="md:col-span-2" data-view-container="categories-admin">
            <!-- Vue tableau -->
            <div data-view-pane="table">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-x-auto">
                    <table class="w-full min-w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-gray-600 text-xs uppercase font-semibold">
                                <th class="px-5 py-4 whitespace-nowrap">Catégorie</th>
                                <th class="px-5 py-4 whitespace-nowrap">Articles</th>
                                <th class="px-5 py-4 text-right whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                        <?php if (empty($categories)): ?>
                            <tr>
                                <td colspan="3" class="px-5 py-10 text-center text-gray-400">
                                    Aucune catégorie pour le moment.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($categories as $cat): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-4 font-medium text-gray-900">
                                    <?= htmlspecialchars($cat['libelle']) ?>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="px-2.5 py-1 rounded-md text-xs font-semibold border bg-blue-50 text-blue-700 border-blue-100">
                                        <?= $cat['nb_articles'] ?> article<?= $cat['nb_articles'] > 1 ? 's' : '' ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex gap-2 justify-end">
                                        <?php $suffixe = 't'; require ROOT . "/views/categories/_actions_categorie.php"; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Vue cartes -->
            <div data-view-pane="card" class="hidden">
                <?php if (empty($categories)): ?>
                    <p class="text-center text-gray-400 py-10">Aucune catégorie pour le moment.</p>
                <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach ($categories as $cat): ?>
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex flex-col">
                        <div class="flex items-center gap-2">
                            <span class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <i class="fa-solid fa-tag"></i>
                            </span>
                            <p class="font-semibold text-gray-900"><?= htmlspecialchars($cat['libelle']) ?></p>
                        </div>
                        <span class="self-start mt-3 px-2.5 py-1 rounded-md text-xs font-semibold border bg-blue-50 text-blue-700 border-blue-100">
                            <?= $cat['nb_articles'] ?> article<?= $cat['nb_articles'] > 1 ? 's' : '' ?>
                        </span>
                        <div class="mt-4 pt-4 border-t border-gray-100 flex gap-2 justify-end">
                            <?php $suffixe = 'c'; require ROOT . "/views/categories/_actions_categorie.php"; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
<?php require_once ROOT . "/views/partials/pagination.php"; ?>
