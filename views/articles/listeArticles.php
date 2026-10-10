<div class="max-w-6xl mx-auto my-8 px-4">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 pb-4 border-b border-gray-200 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Gestion des articles</h2>
            <p class="text-sm text-gray-500 mt-1">Publiez, rejetez ou bannissez les articles signalés.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <!-- Filtre par statut -->
            <div class="flex gap-2 flex-wrap">
                <?php
                $statuts = ["" => "Tous", "En attente" => "En attente", "Publie" => "Publiés", "Rejete" => "Rejetés"];
                foreach ($statuts as $val => $label):
                    $active = ($statut_filtre ?? "") === $val;
                ?>
                <a href="<?= $val ? path('article', 'listeAdmin', ['statut' => $val]) : path('article', 'listeAdmin') ?>"
                   class="px-3 py-1.5 rounded-lg text-sm font-medium border transition
                   <?= $active ? 'bg-[#1A237E] text-white border-[#1A237E]' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' ?>">
                    <?= $label ?>
                </a>
                <?php endforeach; ?>
            </div>
            <?php viewToggle('articles-admin'); ?>
        </div>
    </div>

    <div data-view-container="articles-admin">
        <!-- Vue tableau -->
        <div data-view-pane="table">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
                <table class="w-full min-w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-gray-600 text-xs uppercase font-semibold">
                            <th class="px-5 py-4 whitespace-nowrap">Titre</th>
                            <th class="px-5 py-4 whitespace-nowrap">Auteur</th>
                            <th class="px-5 py-4 whitespace-nowrap">Catégorie</th>
                            <th class="px-5 py-4 whitespace-nowrap">Signalements</th>
                            <th class="px-5 py-4 whitespace-nowrap">Statut</th>
                            <th class="px-5 py-4 text-right whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    <?php if (empty($articles)): ?>
                        <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">Aucun article trouvé.</td></tr>
                    <?php else: ?>
                        <?php foreach ($articles as $art): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-4 font-medium text-gray-900 max-w-xs truncate">
                                <?= htmlspecialchars($art['titre']) ?>
                                <?php if ($art['nb_commentaires'] > 0): ?>
                                    <span class="ml-2 text-xs text-gray-400"><?= $art['nb_commentaires'] ?> comm.</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-5 py-4">
                                <?php if ($art['banni']): ?>
                                    <span class="text-red-500 line-through"><?= htmlspecialchars($art['utilisateur_nom']) ?></span>
                                    <span class="ml-1 text-xs text-red-400">(banni)</span>
                                <?php else: ?>
                                    <?= htmlspecialchars($art['utilisateur_nom']) ?>
                                <?php endif; ?>
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs bg-blue-50 text-[#1A237E] border border-blue-100">
                                    <?= htmlspecialchars($art['categorie_nom']) ?>
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <?php $nb = (int)$art['nb_signalements']; ?>
                                <span class="px-2.5 py-1 rounded-md text-xs font-semibold border
                                    <?= $nb >= 5 ? 'bg-red-50 text-red-700 border-red-200' : 'bg-gray-50 text-gray-600 border-gray-200' ?>">
                                    <?= $nb ?> <?= $nb >= 5 ? '<i class="fa-solid fa-triangle-exclamation ml-1"></i>' : '' ?>
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <?php
                                $cls = "bg-yellow-50 text-yellow-700 border-yellow-100";
                                if ($art['statut'] === 'Publie')  $cls = "bg-green-50 text-green-700 border-green-100";
                                if ($art['statut'] === 'Rejete') $cls = "bg-red-50 text-red-700 border-red-100";
                                ?>
                                <span class="px-2.5 py-1 rounded-md text-xs font-semibold border <?= $cls ?>">
                                    <?= htmlspecialchars($art['statut']) ?>
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex gap-2 justify-end flex-wrap">
                                    <?php $suffixe = 't'; require ROOT . "/views/articles/_actions_article.php"; ?>
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
            <?php if (empty($articles)): ?>
                <p class="text-center text-gray-400 py-12">Aucun article trouvé.</p>
            <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php foreach ($articles as $art): ?>
                <?php
                $cls = "bg-yellow-50 text-yellow-700 border-yellow-100";
                if ($art['statut'] === 'Publie')  $cls = "bg-green-50 text-green-700 border-green-100";
                if ($art['statut'] === 'Rejete') $cls = "bg-red-50 text-red-700 border-red-100";
                $nb  = (int)$art['nb_signalements'];
                ?>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition flex flex-col">
                    <div class="h-40 bg-gray-100 overflow-hidden">
                        <?php if (!empty($art['image'])): ?>
                        <img src="<?= WEBROOT ?>uploads/<?= htmlspecialchars($art['image']) ?>"
                             alt="<?= htmlspecialchars($art['titre']) ?>"
                             onerror="this.onerror=null;this.src='<?= WEBROOT ?>uploads/image-indisponible.svg'"
                             class="w-full h-full object-cover">
                        <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">Pas d'image</div>
                        <?php endif; ?>
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        <span class="self-start inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-blue-50 text-[#1A237E] border border-blue-100">
                            <?= htmlspecialchars($art['categorie_nom']) ?>
                        </span>
                        <h3 class="font-semibold text-gray-900 mt-2 line-clamp-2"><?= htmlspecialchars($art['titre']) ?></h3>
                        <p class="text-xs text-gray-500 mt-1">
                            Par <?= htmlspecialchars($art['utilisateur_nom']) ?>
                            <?= $art['banni'] ? '<span class="text-red-400">(banni)</span>' : '' ?>
                        </p>
                        <div class="flex items-center gap-2 mt-2 flex-wrap">
                            <span class="px-2.5 py-1 rounded-md text-xs font-semibold border <?= $cls ?>">
                                <?= htmlspecialchars($art['statut']) ?>
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-xs font-semibold border bg-gray-50 text-gray-600 border-gray-200">
                                <i class="fa-solid fa-flag"></i> <?= $nb ?>
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-xs font-semibold border bg-pink-50 text-pink-600 border-pink-100">
                                <i class="fa-solid fa-heart"></i> <?= (int)$art['nb_jaimes'] ?>
                            </span>
                        </div>
                        <div class="mt-auto pt-3 flex gap-2 flex-wrap">
                            <?php $suffixe = 'c'; require ROOT . "/views/articles/_actions_article.php"; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require_once ROOT . "/views/partials/pagination.php"; ?>
