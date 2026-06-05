<div class="max-w-6xl mx-auto my-8 px-4">

    <!-- En-tête avec bouton d'ajout -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 pb-4 border-b border-gray-200 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Liste des Administrateurs</h2>
            <p class="text-sm text-gray-500 mt-1">
                Gestion des utilisateurs possédant un accès complet à la plateforme.
            </p>
        </div>
        <div>
            <a href="#" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                <i class="fas fa-plus"></i>
                Ajouter un administrateur
            </a>
        </div>
    </div>

    <!-- Tableau des administrateurs -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
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
                                <!-- Prénom + Nom -->
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
                                <!-- Email -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <?= htmlspecialchars($admin['email']) ?>
                                </td>
                                <!-- Rôle -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        <?= htmlspecialchars($admin['role']) ?>
                                    </span>
                                </td>
                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-4 text-base">
                                        <a href="#" class="text-gray-400 hover:text-indigo-600 transition" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" class="text-gray-400 hover:text-red-600 transition" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- État vide -->
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
