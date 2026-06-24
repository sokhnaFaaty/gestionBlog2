<div class="max-w-6xl mx-auto my-8 px-4">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 pb-4 border-b border-gray-200">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Gestion des articles</h2>
            <p class="text-sm text-gray-500 mt-1">Publiez, rejetez ou bannissez les articles signalés.</p>
        </div>
        <!-- Filtre par statut -->
        <div class="mt-4 md:mt-0 flex gap-2 flex-wrap">
            <?php
            $statuts = ["" => "Tous", "En attente" => "En attente", "Publie" => "Publiés", "Rejete" => "Rejetés"];
            foreach ($statuts as $val => $label):
                $active = ($statut_filtre ?? "") === $val;
            ?>
            <a href="<?= $val ? path('admin', 'listeArticles', ['statut' => $val]) : path('admin', 'listeArticles') ?>"
               class="px-3 py-1.5 rounded-lg text-sm font-medium border transition
               <?= $active ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' ?>">
                <?= $label ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-gray-600 text-xs uppercase font-semibold">
                    <th class="px-5 py-4">Titre</th>
                    <th class="px-5 py-4">Auteur</th>
                    <th class="px-5 py-4">Catégorie</th>
                    <th class="px-5 py-4">Signalements</th>
                    <th class="px-5 py-4">Statut</th>
                    <th class="px-5 py-4 text-right">Actions</th>
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
                        <span class="px-2.5 py-0.5 rounded-full text-xs bg-blue-50 text-blue-700 border border-blue-100">
                            <?= htmlspecialchars($art['categorie_nom']) ?>
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <?php $nb = (int)$art['nb_signalements']; ?>
                        <span class="px-2.5 py-1 rounded-md text-xs font-semibold border
                            <?= $nb >= 5 ? 'bg-red-50 text-red-700 border-red-200' : 'bg-gray-50 text-gray-600 border-gray-200' ?>">
                            <?= $nb ?> <?= $nb >= 5 ? '⚠️' : '' ?>
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
                            <!-- Publier -->
                            <?php if ($art['statut'] !== 'Publie'): ?>
                            <form method="POST" action="<?= path('admin', 'changerStatut') ?>">
                                <input type="hidden" name="id_article" value="<?= $art['id_article'] ?>">
                                <input type="hidden" name="statut" value="Publie">
                                <button class="px-3 py-1.5 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition">Publier</button>
                            </form>
                            <?php endif; ?>
                            <!-- Rejeter -->
                            <?php if ($art['statut'] !== 'Rejete'): ?>
                            <form method="POST" action="<?= path('admin', 'changerStatut') ?>">
                                <input type="hidden" name="id_article" value="<?= $art['id_article'] ?>">
                                <input type="hidden" name="statut" value="Rejete">
                                <button class="px-3 py-1.5 bg-red-500 text-white text-xs rounded-lg hover:bg-red-600 transition">Rejeter</button>
                            </form>
                            <?php endif; ?>
                            <!-- Bannir si signalements >= 5 -->
                            <?php if ((int)$art['nb_signalements'] >= 5 && $art['statut'] !== 'Rejete'): ?>
                            <form id="form-banir-art-<?= $art['id_article'] ?>"
                                  method="POST" action="<?= path('admin', 'banirArticle') ?>" class="hidden">
                                <input type="hidden" name="id_article" value="<?= $art['id_article'] ?>">
                            </form>
                            <button type="button"
                                    onclick="confirmerAction(this)"
                                    data-form="form-banir-art-<?= $art['id_article'] ?>"
                                    data-message="Bannir cet article signalé ?"
                                    class="px-3 py-1.5 bg-orange-600 text-white text-xs rounded-lg hover:bg-orange-700 transition">
                                    Bannir
                            </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>