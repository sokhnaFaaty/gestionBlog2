<div class="max-w-6xl mx-auto my-8 px-4">
    <!-- En-tête de la page -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 pb-4 border-b border-gray-200">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Mes Articles</h2>
            <p class="text-sm text-gray-500 mt-1">Retrouvez la liste et le statut de vos publications.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="<?=path("admins","ajout")?>"
                class="inline-flex items-center px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
                <span class="mr-2 text-base">+</span> Écrire un article
            </a>
        </div>
    </div>

    <!-- Tableau des articles -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-600 text-xs uppercase font-semibold">
                        <th class="px-6 py-4">Titre</th>
                        <th class="px-6 py-4">Image</th>
                        <th class="px-6 py-4">Catégorie</th>
                        <th class="px-6 py-4">Date de création</th>
                        <th class="px-6 py-4">Statut</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    <?php if (empty($articles)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-medium">
                                📁 Aucun article rédigé pour le moment.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($articles as $article): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-semibold text-gray-900">
                                    <?= htmlspecialchars($article['titre']) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <img src="/uploads/<?= $article['image']; ?>" alt="<?= $article['titre']; ?>" width="50"
                                        class="w-12 h-12 object-cover rounded-lg border border-gray-200">

                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                        <?= htmlspecialchars($article['categorie_nom']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    <?= date('d/m/Y H:i', strtotime($article['date_publication'])) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    // Style dynamique pour le badge de statut
                                    $statusClass = "bg-yellow-50 text-yellow-700 border-yellow-100"; // Par défaut En attente
                                    if ($article['statut'] === 'Publie') {
                                        $statusClass = "bg-green-50 text-green-700 border-green-100";
                                    } elseif ($article['statut'] === 'Rejete') {
                                        $statusClass = "bg-red-50 text-red-700 border-red-100";
                                    }
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold border <?= $statusClass ?>">
                                        <?= htmlspecialchars($article['statut']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="<?= path('auteur', 'edit', ['id' => $article['id_article']]) ?>"
                                        class="text-indigo-600 hover:text-indigo-900 font-medium transition">Modifier</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>