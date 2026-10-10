<div class="max-w-4xl mx-auto my-8 px-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 pb-4 border-b border-gray-200 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Newsletter</h2>
            <p class="text-sm text-gray-500 mt-1">
                <?= $total ?> abonné<?= $total > 1 ? 's' : '' ?> inscrit<?= $total > 1 ? 's' : '' ?>
            </p>
        </div>
        <?php viewToggle('newsletter-admin'); ?>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Adresse email</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Date d'inscription</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (!empty($newsletters)): ?>
                        <?php foreach ($newsletters as $i => $row): ?>
                            <tr class="hover:bg-gray-50/75 transition">
                                <td class="px-6 py-4 text-sm text-gray-400"><?= (($page - 1) * $parPage) + $i + 1 ?></td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                    <?= htmlspecialchars($row['email']) ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <?= date('d/m/Y à H:i', strtotime($row['date_inscription'])) ?>
                                </td>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (!empty($newsletters)): ?>
                                <?php foreach ($newsletters as $i => $row): ?>
                                    <tr class="hover:bg-gray-50/75 transition">
                                        <td class="px-6 py-4 text-sm text-gray-400"><?= $i + 1 ?></td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                            <?= htmlspecialchars($row['email']) ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <?= date('d/m/Y à H:i', strtotime($row['date_inscription'])) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                        <div class="text-2xl text-gray-300 mb-2">
                                            <i class="fas fa-envelope-open-text"></i>
                                        </div>
                                        <span class="text-sm font-medium block">Aucun abonné pour l'instant</span>
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
            <?php if (!empty($newsletters)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($newsletters as $row): ?>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate"><?= htmlspecialchars($row['email']) ?></p>
                        <p class="text-xs text-gray-400"><?= date('d/m/Y à H:i', strtotime($row['date_inscription'])) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
                <p class="text-center text-gray-500 py-12">Aucun abonné pour l'instant</p>
            <?php endif; ?>
        </div>
    </div>

    <?php require_once ROOT . "/views/partials/pagination.php"; ?>
</div>
