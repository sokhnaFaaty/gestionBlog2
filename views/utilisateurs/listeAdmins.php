<div class="max-w-6xl mx-auto my-8 px-4">
    <!-- En-tête avec bouton d'ajout -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 pb-4 border-b border-gray-200 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Liste des Administrateurs</h2>
            <p class="text-sm text-gray-500 mt-1">
            Gestion des utilisateurs possédant un accès complet à la plateforme.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <?php viewToggle('admins-admin'); ?>
            <a href="<?= path('utilisateur', 'addAdmin') ?>"
 class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                <i class="fas fa-plus"></i>
                Ajouter un administrateur
            </a>
        </div>
    </div>

    <div data-view-container="admins-admin">
        <!-- Vue tableau -->
        <div data-view-pane="table">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Identité</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Adresse email</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Rôle</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (!empty($admins)): ?>
                                <?php foreach ($admins as $admin): ?>
                                    <tr class="hover:bg-gray-50/75 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 bg-indigo-50 border border-indigo-100 text-indigo-600 font-semibold rounded-full flex items-center justify-center text-sm uppercase">
                                                    <?= mb_substr(htmlspecialchars($admin['prenom']), 0, 1) . mb_substr(htmlspecialchars($admin['nom']), 0, 1) ?>
                                                </div>
                                                <div>
                                                    <span class="block font-medium text-gray-900">
                                                        <?= htmlspecialchars($admin['prenom']) ?> <?= htmlspecialchars($admin['nom']) ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            <?= htmlspecialchars($admin['email']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                <?= htmlspecialchars($admin['role']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-4 text-base">
                                                <?php $suffixe = 't'; require ROOT . "/views/utilisateurs/_action_admin.php"; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                        <div class="text-2xl text-gray-300 mb-2">
                                            <i class="fas fa-users-slash"></i>
                                        </div>
                                        <span class="text-sm font-medium block">Aucun administrateur trouvé</span>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Vue cartes -->
        <div data-view-pane="card" class="hidden">
            <?php if (!empty($admins)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php foreach ($admins as $admin): ?>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex flex-col">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 bg-indigo-50 border border-indigo-100 text-indigo-600 font-bold rounded-full flex items-center justify-center uppercase">
                            <?= mb_substr(htmlspecialchars($admin['prenom']), 0, 1) . mb_substr(htmlspecialchars($admin['nom']), 0, 1) ?>
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-900 truncate">
                                <?= htmlspecialchars($admin['prenom']) ?> <?= htmlspecialchars($admin['nom']) ?>
                            </p>
                            <p class="text-xs text-gray-500 truncate"><?= htmlspecialchars($admin['email']) ?></p>
                        </div>
                    </div>
                    <span class="self-start mt-4 inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                        <?= htmlspecialchars($admin['role']) ?>
                    </span>
                    <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end text-base">
                        <?php $suffixe = 'c'; require ROOT . "/views/utilisateurs/_action_admin.php"; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
                <p class="text-center text-gray-500 py-12">Aucun administrateur trouvé</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require_once ROOT . "/views/partials/pagination.php"; ?>
