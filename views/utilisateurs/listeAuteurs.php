<div class="max-w-5xl mx-auto my-8 px-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 pb-4 border-b border-gray-200 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Gestion des auteurs</h2>
            <p class="text-sm text-gray-500 mt-1">Consultez les profils et bannissez les auteurs en infraction.</p>
        </div>
        <?php viewToggle('auteurs-admin'); ?>
    </div>

    <div data-view-container="auteurs-admin">
        <!-- Vue tableau -->
        <div data-view-pane="table">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
                <table class="w-full min-w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-gray-600 text-xs uppercase font-semibold">
                            <th class="px-5 py-4 whitespace-nowrap">Nom</th>
                            <th class="px-5 py-4 whitespace-nowrap">Email</th>
                            <th class="px-5 py-4 whitespace-nowrap">Articles</th>
                            <th class="px-5 py-4 whitespace-nowrap">Statut</th>
                            <th class="px-5 py-4 text-right whitespace-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    <?php foreach ($auteurs as $auteur): ?>
                    <tr class="hover:bg-gray-50 transition <?= $auteur['banni'] ? 'opacity-60' : '' ?>">
                        <td class="px-5 py-4 font-medium text-gray-900">
                            <?= htmlspecialchars($auteur['nom']) ?>
                            <?php if ($auteur['banni']): ?>
                                <span class="ml-2 text-xs text-red-500 font-normal">(banni)</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-4 text-gray-500"><?= htmlspecialchars($auteur['email']) ?></td>
                        <td class="px-5 py-4"><?= $auteur['nb_articles'] ?></td>
                        <td class="px-5 py-4">
                            <?php if ($auteur['banni']): ?>
                                <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-red-50 text-red-700 border border-red-100">Banni</span>
                            <?php else: ?>
                                <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-green-50 text-green-700 border border-green-100">Actif</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <?php $suffixe = 't'; require ROOT . "/views/utilisateurs/_action_auteur.php"; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Vue cartes -->
        <div data-view-pane="card" class="hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php foreach ($auteurs as $auteur): ?>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex flex-col <?= $auteur['banni'] ? 'opacity-70' : '' ?>">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-full bg-indigo-50 border border-indigo-100 text-[#1A237E] font-bold flex items-center justify-center uppercase">
                            <?= mb_substr($auteur['nom'], 0, 1) ?>
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-900 truncate"><?= htmlspecialchars($auteur['nom']) ?></p>
                            <p class="text-xs text-gray-500 truncate"><?= htmlspecialchars($auteur['email']) ?></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-4">
                        <?php if ($auteur['banni']): ?>
                            <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-red-50 text-red-700 border border-red-100">Banni</span>
                        <?php else: ?>
                            <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-green-50 text-green-700 border border-green-100">Actif</span>
                        <?php endif; ?>
                        <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-50 text-[#1A237E] border border-blue-100">
                            <?= (int)$auteur['nb_articles'] ?> article(s)
                        </span>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <?php $suffixe = 'c'; require ROOT . "/views/utilisateurs/_action_auteur.php"; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once ROOT . "/views/partials/pagination.php"; ?>
